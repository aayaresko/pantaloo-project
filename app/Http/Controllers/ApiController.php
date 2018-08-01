<?php

namespace App\Http\Controllers;

use App\RawLog;
use App\Token;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Slots\Casino;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\Exception;

class ApiController extends Controller
{
    public function callback(Request $request)
    {
        $raw_log = new RawLog();
        $raw_log->request = json_encode($request->all());
        $raw_log->save();

        $casino = new Casino(env('CASINO_OPERATOR_ID'), env('CASINO_KEY'));

        try {
            //$casino->CheckSignature($request);

            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }

            $ip = $_SERVER['REMOTE_ADDR'];

            if($ip != '46.4.63.60') throw new \Exception('Technical error', 1);
            
            $token = Token::where('token', $request->input('sessionid'))->first();
            if(!$token) $token = Token::where('token', $request->input('gamesessionid'))->first();
            if(!$token) throw new \Exception('Technical error', 1);

            $user = $token->user;

            if(!$user) throw new \Exception('Technical error', 1);

            $resp = [];

            switch ($request->input('request'))
            {
                case 'getaccount':
                    $resp = [
                        'GAMESESSIONID' => $token->token,
                        'ACCOUNTID' => $user->id,
                        'CURRENCY' => 'EUR',
                        'CITY' => 'Berlin',
                        'COUNTRY' => 'DEU'
                    ];
                    break;

                case 'getbalance':
                    if($token->slot->is_bonus == 1) $balance = $user->getBalance();
                    else $balance = $user->getRealBalance();

                    $resp = [
                        'BALANCE' => $balance
                    ];
                    break;
                case 'wager':
                    $sum = $request->input('betamount');
                    $ext_id = $request->input('transactionid');
                    $round_id = $request->input('roundid');

                    if(!is_numeric($round_id)) throw new \Exception('Round id not found');

                    if(!is_numeric($sum)) throw new \Exception('Invalid betsum', 1);
                    if($sum < 0) throw new \Exception('Invalid betsum', 1);

                    if(empty($ext_id)) throw new \Exception('Tranaction id required', 110);

                    $ext_id = $token->slot->id . '-' . $ext_id;

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if($transaction) throw new \Exception('Transaction already exists', 110);

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 1;
                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);

                    $transaction->round_id = $round_id;

                    $transaction->bonus_sum = 0;

                    if($user->balance < $sum)
                    {
                        if(($user->bonus_balance + $user->balance) >= $sum and $token->slot->is_bonus == 1)
                        {
                            $transaction->sum = -1*$user->balance;
                            $transaction->bonus_sum = (-1)*(bcsub($sum, -1*$transaction->sum, 5));

                            if(!bccomp(bcadd($transaction->sum, $transaction->bonus_sum, 5), $sum, 5)) throw new \Exception('Problem with bonus');
                        }
                        else throw new \Exception('Not enogh funds[2]');
                    }
                    else
                    {
                        $transaction->sum = -1*$sum;
                    }

                    $user->changeBalance($transaction);

                    if($token->slot->is_bonus == 1) $balance = $user->getBalance();
                    else $balance = $user->getRealBalance();

                    $resp = [
                        'ACCOUNTTRANSACTIONID' => $transaction->id,
                        'REALMONEYBET' => -1*$transaction->sum,
                        'BONUSMONEYBET' => -1*$transaction->bonus_sum,
                        'BALANCE' => $balance
                    ];
                    break;

                case 'result':
                    sleep(1);

                    $sum = $request->input('result');
                    $ext_id = $request->input('transactionid');
                    $round_id = $request->input('roundid');

                    if(!is_numeric($round_id)) throw new \Exception('Round id not found');
                    if(!is_numeric($sum)) throw new \Exception('Invalid betsum', 1);
                    if($sum < 0) throw new \Exception('Invalid betsum', 1);

                    if(empty($ext_id)) throw new \Exception('Tranaction id required', 110);

                    $ext_id = $token->slot->id . '-' . $ext_id;

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if($transaction) throw new \Exception('Transaction already exists', 110);

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 2;

                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);
                    $transaction->round_id = $round_id;

                    if($user->bonuses()->first()) {
                        $wager_transaction = Transaction::where('token_id', $token->id)->where('type', 1)->orderBy('id', 'DESC')->where(function ($query) {
                            $query->where('sum', '<>', 0)->orWhere('bonus_sum', '<>', 0);
                        })->first();

                        if (!$wager_transaction) throw new \Exception('Wager transaction not found');
                    }
                    else
                    {
                        $wager_transaction = Transaction::where('token_id', $token->id)->where('type', 1)->where('round_id', $request->input('roundid'))->orderBy('id', 'DESC')->first();
                    }


                    if($wager_transaction->bonus_sum != 0) {
                        $transaction->sum = bcmul($sum, ((-1) * bcdiv($wager_transaction->sum, (bcadd((-1) * $wager_transaction->sum, (-1) * $wager_transaction->bonus_sum, 5)), 5)), 5);
                        //$transaction->bonus_sum = $sum*((-1)*$wager_transaction->bonus_sum/((-1)*$wager_transaction->sum + (-1)*$wager_transaction->bonus_sum));
                        $transaction->bonus_sum = bcmul($sum, ((-1) * bcdiv($wager_transaction->bonus_sum, (bcadd((-1) * $wager_transaction->sum, (-1) * $wager_transaction->bonus_sum, 5)), 5)), 5);
                    }
                    else
                    {
                        $transaction->bonus_sum = 0;
                        $transaction->sum = $sum;
                    }
                    
                    $user->changeBalance($transaction);

                    if($token->slot->is_bonus == 1) $balance = $user->getBalance();
                    else $balance = $user->getRealBalance();

                    $resp = [
                        'ACCOUNTTRANSACTIONID' => $transaction->id,
                        'BALANCE' => $balance
                    ];
                    break;
                case 'rollback':
                    $sum = $request->input('rollbackamount');
                    $ext_id = $request->input('transactionid');

                    if(!is_numeric($sum)) throw new \Exception('Invalid betsum', 1);
                    if($sum < 0) throw new \Exception('Invalid betsum', 1);

                    if(empty($ext_id)) throw new \Exception('Tranaction id required', 102);

                    $ext_id = $token->slot->id . '-' . $ext_id;

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if(!$transaction) throw new \Exception('Transaction not found', 102);

                    if($transaction->type != 1) throw new \Exception('Operation not allowed', 110);

                    if($user->id != $transaction->user_id) throw new \Exception('Operation not allowed', 110);

                    $user->changeBalance($transaction, true);

                    if($token->slot->is_bonus == 1) $balance = $user->getBalance();
                    else $balance = $user->getRealBalance();

                    $resp = [
                        'ACCOUNTTRANSACTIONID' => $transaction->id,
                        'BALANCE' => $user->balance
                    ];
                    break;
                default: throw new \Exception('No such method');
            }

            $response = $casino->Response($request->input('request'), $resp);
        }
        catch (\Exception $e)
        {
            $code = $e->getCode();
            if(!$code) $code = 1;

            $response = $casino->Response($request->input('request'), [], [
                'msg' => $e->getMessage(),
                'code' => $code
            ]);
        }

        $raw_log->response = $response;
        $raw_log->save();

        return response()->make($response, 200, [
            'Content-Type: text/xml',
            'Cache-Control: max-age=0'
        ]);
    }
}
