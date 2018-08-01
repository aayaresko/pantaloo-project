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

class FreeSpinsController extends Controller
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

                    $resp = [
                        'BALANCE' => $user->free_spins
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

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if($transaction) throw new \Exception('Transaction already exists', 110);

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 9;
                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);

                    $transaction->round_id = $round_id;

                    $transaction->bonus_sum = 0;
                    $transaction->sum = 0;
                    if($sum > 0) $transaction->free_spin = -1;
                    else $transaction->free_spin = 0;

                    $user->changeBalance($transaction);

                    $balance = $user->free_spins;

                    $resp = [
                        'ACCOUNTTRANSACTIONID' => $transaction->id,
                        'REALMONEYBET' => -1*$sum,
                        'BONUSMONEYBET' => 0,
                        'BALANCE' => $balance
                    ];
                    break;

                case 'result':
                    $sum = $request->input('result');
                    $ext_id = $request->input('transactionid');
                    $round_id = $request->input('roundid');

                    if(!is_numeric($round_id)) throw new \Exception('Round id not found');
                    if(!is_numeric($sum)) throw new \Exception('Invalid betsum', 1);
                    if($sum < 0) throw new \Exception('Invalid betsum', 1);

                    if(empty($ext_id)) throw new \Exception('Tranaction id required', 110);

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if($transaction) throw new \Exception('Transaction already exists', 110);

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 10;

                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);
                    $transaction->round_id = $round_id;

                    $transaction->bonus_sum = $sum;

                    $user->changeBalance($transaction);

                    $balance = $user->free_spins;

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

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if(!$transaction) throw new \Exception('Transaction not found', 102);

                    if($transaction->type != 9) throw new \Exception('Operation not allowed', 110);

                    if($user->id != $transaction->user_id) throw new \Exception('Operation not allowed', 110);

                    $user->changeBalance($transaction, true);

                    $balance = $user->free_spins;

                    $resp = [
                        'ACCOUNTTRANSACTIONID' => $transaction->id,
                        'BALANCE' => $balance
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
