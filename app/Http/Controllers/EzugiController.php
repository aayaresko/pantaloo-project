<?php

namespace App\Http\Controllers;

use App\Token;
use App\RawLog;
use App\Rollback;
use App\PublicToken;
use App\Slots\Ezugi;
use App\Transaction;
use App\Http\Requests;

use Illuminate\Http\Request;

class EzugiController extends Controller
{
    public function callback(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $raw_log = new RawLog();
        $raw_log->request = $request->getContent();
        $raw_log->save();

        try {
            if ($input['operatorId'] != env('EZUGI_OPERATOR_ID')) {
                throw new \Exception('General error', 1);
            }
            //if(!isset($input['request'])) throw new \Exception('General error', 1);

            //if($request->ip() != '46.4.63.60') throw new \Exception('Technical error', 1);

            $resp = [
                'operatorId' => env('EZUGI_OPERATOR_ID'),
                'timestamp' => Ezugi::GetTime(),
            ];

            if ($request->input('request') == 'auth') {
                $public_token = PublicToken::where('token', $input['token'])->first();
                //if(!$token) $token = Token::where('token', $request->input('gamesessionid'))->first();
                if (! $public_token) {
                    throw new \Exception('Token not found', 6);
                }

                $user = $public_token->user;

                if (! $user) {
                    throw new \Exception('User not found', 7);
                }
            } else {
                $token = Token::where('token', $input['token'])->first();
                //if(!$token) $token = Token::where('token', $request->input('gamesessionid'))->first();
                if (! $token) {
                    throw new \Exception('Token not found', 6);
                }

                $user = $token->user;

                if (! $user) {
                    throw new \Exception('User not found', 7);
                }
            }

            $resp['uid'] = (string) $user->id;
            $raw_log->user_id = $user->id;

            switch ($request->input('request')) {
                case 'auth':

                    if ($user->id == 25) {
                        $private_token = '58c118028c36c468776592-4e8e57ab0d';
                    } else {
                        $token = $public_token->token();
                        $private_token = $token->token;
                    }

                    $resp = [
                        'operatorId' => env('EZUGI_OPERATOR_ID'),
                        'uid' => (string) $user->id,
                        'nickName' => preg_replace('|[^A-z0-9]*|isUS', '', preg_replace('|@.*$|isUS', '', $user->email)),
                        'token' => $private_token,
                        'playerTokenAtLaunch' => $public_token->token,
                        'balance' => $user->getRealBalance(),
                        'currency' => 'mBTC',
                        'language' => 'en',
                        'errorCode' => 0,
                        'errorDescription' => 'OK',
                        'timestamp' => Ezugi::GetTime(),
                        'clientIP' => '127.0.0.1',
                    ];

                    break;

                case 'debit':
                    $sum = $input['debitAmount'];
                    $ext_id = $input['transactionId'];
                    $round_id = $input['roundId'];

                    $resp['roundId'] = $round_id;
                    $resp['token'] = $token->token;
                    $resp['balance'] = $user->getRealBalance();
                    $resp['currency'] = 'mBTC';
                    $resp['transactionId'] = $ext_id;

                    $rollback = Rollback::where('ext_id', $ext_id)->first();
                    if ($rollback) {
                        throw new \Exception('Debit after rollback / General Error', 1);
                    }

                    if (! is_numeric($round_id)) {
                        throw new \Exception('General error', 1);
                    }

                    if (! is_numeric($sum)) {
                        throw new \Exception('General error', 1);
                    }
                    if ($sum < 0) {
                        throw new \Exception('General error', 1);
                    }

                    if (empty($ext_id)) {
                        throw new \Exception('General error', 1);
                    }

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if ($transaction) {
                        throw new \Exception('Transaction has already processed', 0);
                    }

                    if ((string) $input['uid'] != (string) $user->id) {
                        throw new \Exception('User not found', 7);
                    }

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 1;
                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);
                    $transaction->round_id = $round_id;
                    $transaction->bonus_sum = 0;

                    if ($user->balance < $sum) {
                        throw new \Exception('Insufficient funds', 3);
                    }

                    $transaction->sum = -1 * $sum;

                    try {
                        $user->changeBalance($transaction);
                    } catch (\Exception $e) {
                        throw new \Exception('Insufficient funds', 3);
                    }

                    $balance = $user->getRealBalance();

                    $resp['balance'] = $balance;

                    $resp['errorCode'] = 0;
                    $resp['errorDescription'] = 'OK';
                    $resp['timestamp'] = Ezugi::GetTime();

                    break;

                case 'credit':
                    $sum = $input['creditAmount'];
                    $ext_id = $input['transactionId'];
                    $round_id = $input['roundId'];

                    $resp['roundId'] = $round_id;
                    $resp['token'] = $token->token;
                    $resp['balance'] = $user->getRealBalance();
                    $resp['transactionId'] = $ext_id;
                    $resp['currency'] = 'mBTC';
                    $resp['bonusAmount'] = 0;
                    $resp['timestamp'] = Ezugi::GetTime();

                    if (! $round_id) {
                        throw new \Exception('General error', 1);
                    }
                    if (! is_numeric($sum)) {
                        throw new \Exception('General error', 1);
                    }
                    if ($sum < 0) {
                        throw new \Exception('General error', 1);
                    }

                    if (empty($ext_id)) {
                        throw new \Exception('General error', 1);
                    }

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if ($transaction) {
                        throw new \Exception('Transaction has already processed', 0);
                    }

                    if ((string) $input['uid'] != (string) $user->id) {
                        throw new \Exception('User not found', 7);
                    }

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 2;

                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);
                    $transaction->round_id = $round_id;

                    $transaction->sum = $sum;
                    $transaction->bonus_sum = 0;

                    $user->changeBalance($transaction);

                    $balance = $user->getRealBalance();

                    $resp['errorCode'] = 0;
                    $resp['errorDescription'] = 'OK';
                    $resp['timestamp'] = Ezugi::GetTime();
                    $resp['balance'] = $balance;

                    break;
                case 'rollback':
                    $sum = $input['rollbackAmount'];
                    $ext_id = $input['transactionId'];
                    $round_id = $input['roundId'];

                    $resp['roundId'] = $round_id;
                    $resp['token'] = $token->token;
                    $resp['balance'] = $user->getRealBalance();
                    $resp['transactionId'] = $ext_id;
                    $resp['currency'] = 'mBTC';
                    $resp['bonusAmount'] = 0;
                    $resp['timestamp'] = Ezugi::GetTime();

                    if ((string) $input['uid'] != (string) $user->id) {
                        throw new \Exception('User not found', 7);
                    }

                    if (! $round_id) {
                        throw new \Exception('General error', 1);
                    }
                    if (! is_numeric($sum)) {
                        throw new \Exception('General error', 1);
                    }
                    if ($sum < 0) {
                        throw new \Exception('General error', 1);
                    }

                    if (empty($ext_id)) {
                        throw new \Exception('General error', 1);
                    }

                    $rollback = Rollback::where('ext_id', $ext_id)->first();

                    if (! $rollback) {
                        $rollback = new Rollback();
                        $rollback->ext_id = $ext_id;
                        $rollback->save();
                    }

                    $transaction = Transaction::withTrashed()->where('ext_id', $ext_id)->first();

                    if (! $transaction) {
                        throw new \Exception('Transaction not found', 9);
                    } elseif ($transaction->trashed()) {
                        throw new \Exception('Transaction has already processed', 0);
                    }

                    if ($transaction->type != 1) {
                        throw new \Exception('General error', 1);
                    }
                    if ($transaction->sum != -1 * $sum) {
                        throw new \Exception('General error', 1);
                    }
                    if ($user->id != $transaction->user_id) {
                        throw new \Exception('General error', 1);
                    }

                    $user->changeBalance($transaction, true);

                    $balance = $user->getRealBalance();

                    $resp['errorCode'] = 0;
                    $resp['errorDescription'] = 'OK';
                    $resp['timestamp'] = Ezugi::GetTime();
                    $resp['balance'] = $balance;

                    break;
                default: throw new \Exception('No such method');
            }
        } catch (\Exception $e) {
            $code = $e->getCode();
            if (! $code) {
                $code = 1;
            }

            $resp['errorCode'] = $e->getCode();
            $resp['errorDescription'] = $e->getMessage();

            $raw_log->response = $e->getTraceAsString();
            $raw_log->save();

            return response()->json($resp);
            exit;
        }

        $raw_log->response = json_encode($resp);
        $raw_log->save();

        return response()->json($resp);
    }
}
