<?php

namespace App\Slots;

use App\Slot;
use App\User;
use App\Token;
use App\PublicToken;
use Illuminate\Support\Facades\Auth;

class Ezugi
{
    public $operator_id;

    public $lobby_url = false;

    public $currency = 'mBtc';

    public $lobbies = [
        'debug' => 'https://lobby-int.ezugi.com/game/auth/',
        'europe' => 'https://lobby.livetablesbg.com/game/auth/',
        'baltic' => 'https://lobby.livetableslv.com/game/auth/',
        //'asia' => 'https://lobby.livetablecam.com/game/auth/',
        'latin' => 'https://tableslive.com/game/auth/',
        'belgium' => 'https://lobby.magiclivedealers.com/game/auth/',
    ];

    public function __construct()
    {
        $this->operator_id = env('EZUGI_OPERATOR_ID');
    }

    public function getStartUrl(Slot $slot, $game_id = null, $lobby = false, $language = 'en', $demo = false)
    {
        if (! isset($this->lobbies[$slot->path])) {
            throw new \Exception('Link not found');
        }

        $this->lobby_url = $this->lobbies[$slot->path];

        if (! $demo) {
            $user = Auth::user();

            $public_token = new PublicToken();
            $public_token->generate($user);
            $public_token->user()->associate($user);
            $public_token->slot()->associate($slot);
            $public_token->save();

            $data = [
                'operatorId' => $this->operator_id,
                'token' => $public_token->token,
                'language' => $language,
                'clientType' => 'html5',
            ];
        } else {
            $data = [
                'operatorId' => $this->operator_id,
                'token' => '58c12002256471410532577-e8b83d1ffe',
                'language' => $language,
                'clientType' => 'html5',
            ];
        }

        if ($lobby) {
            if ($game_id != null) {
                $data['selectGame'] = $game_id;
            }
        } else {
            if ($game_id != null) {
                $data['openTable'] = $game_id;
            }
        }

        if (! $this->lobby_url) {
            throw new Exception('Unknown lobby type');
        }

        $url = $this->lobby_url.'?'.http_build_query($data);

        return ['url' => $url];
    }

    public function GetUserId()
    {
        if (! isset($_SESSION['user']['uid'])) {
            throw new Exception('Auth required');
        }

        return $_SESSION['user']['uid'];
    }

    public static function IsTokenValid($token, $is_temp)
    {
        $res = PDO_wrap::getArray('SELECT * FROM casino_tokens WHERE token=:token AND is_temp=:is_temp AND expired_time>:time LIMIT 1', [
            'token' => $token,
            'is_temp' => $is_temp,
            'time' => time(),
        ]);

        if (count($res) > 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    public static function GetTime()
    {
        return round(microtime(true) * 1000);
    }

    public function Auth($data)
    {
        $response = [
            'operatorId' => $this->operator_id,
            'uid' => '',
            'nickName' => '',
            'token' => '',
            'playerTokenAtLaunch' => $data['token'],
            'balance' => '',
            'currency' => $this->currency,
            'language' => 'en',
            'clientIP' => '',
            'errorCode' => '0',
            'errorDescription' => 'Completed successfully',
            'timestamp' => self::GetTime(),
        ];

        if ($token_data = self::IsTokenValid($data['token'], 1)) {
            if ($user = self::GetUserInfo($token_data['user_id'])) {
                $response['uid'] = $token_data['user_id'];

                if (preg_match('|^[A-z\s]*$|isUS', $user['user_name'])) {
                    $response['nickName'] = $user['user_name'];
                } else {
                    $response['nickName'] = 'user_'.$token_data['user_id'];
                }

                $response['token'] = $this->GetToken(0, $token_data['user_id'], $token_data['client_ip']);
                $response['balance'] = $user['balance'];
                $response['clientIP'] = $token_data['client_ip'];
            } else {
                $response['errorCode'] = '7';
                $response['errorDescription'] = 'User not found';
            }
        } else {
            $response['errorCode'] = '6';
            $response['errorDescription'] = 'Token not found';
        }

        return $response;
    }

    public static function GetUserInfo($user_id)
    {
        $res = PDO_wrap::getArray('SELECT * FROM users WHERE id=:id', ['id' => $user_id]);

        if (count($res) > 0) {
            $user = $res[0];
            $user['balance'] = $user['credit1'];

            return $user;
        } else {
            return false;
        }
    }

    public function SetUserBalance($user_id, $balance, $add = 0)
    {
        PDO_wrap::Change('UPDATE users SET credit1=credit1+:add WHERE id=:id', [
            'id' => $user_id,
            'add' => $add,
        ]);
        /*
        PDO_wrap::Change("UPDATE users SET credit1=:balance WHERE id=:id", array(
            'id' => $user_id,
            'balance' => $balance
        ));
        */
    }

    public function GetTransaction($method, $transaction_id)
    {
        $res = PDO_wrap::getArray('SELECT * FROM casino_transactions WHERE trans_type=:method AND transactionId=:trans_id LIMIT 1', [
            'method' => $method,
            'trans_id' => $transaction_id,
        ]);

        if (count($res) > 0) {
            return $res[0];
        } else {
            return false;
        }
    }

    public function SaveTransaction($method, $data, $token_data, $new_balance, $amount)
    {
        $vars = [
            'user_id',
            'transactionId',
            'operatorId',
            'token',
            'gameId',
            'serverId',
            'roundId',
            'SeatId',
            'betTypeId',
            'currency',
            'trans_type',
            'balance',
            'amount',
            'error_code',
            'error_descr',
            //'datetime',
            'server_ip',
            'client_ip',
        ];

        $result = [];

        foreach ($vars as $key) {
            if (isset($data[$key])) {
                $result[$key] = $data[$key];
            } else {
                $result[$key] = '';
            }
        }

        $result['currency'] = $this->currency;
        $result['user_id'] = $token_data['user_id'];
        $result['trans_type'] = $method;
        $result['balance'] = $new_balance;
        $result['error_code'] = 0;
        $result['error_descr'] = 'ok';
        $result['server_ip'] = self::GetIP();
        $result['client_ip'] = $token_data['client_ip'];
        $result['amount'] = $amount;

        PDO_wrap::Change('INSERT INTO casino_transactions(user_id, transactionId, operatorId, token, gameId, serverId, roundId, SeatId, betTypeId, currency, trans_type, balance, amount, error_code, error_descr, datetime, server_ip, client_ip) VALUES(:user_id, :transactionId, :operatorId, :token, :gameId, :serverId, :roundId, :SeatId, :betTypeId, :currency, :trans_type, :balance, :amount, :error_code, :error_descr, NOW(), :server_ip, :client_ip)', $result);
    }

    public function Debit($data)
    {
        if ($data['operatorId'] != $this->operator_id) {
            throw new Exception('Invalid operator');
        }
        if (empty($data['transactionId'])) {
            throw new Exception('Transaction not found');
        }
        if (empty($data['debitAmount']) or $data['debitAmount'] < 0) {
            throw new Exception('Invalid amount');
        }

        $response = [
            'operatorId' => $this->operator_id,
            'roundId' => $data['roundId'],
            'uid' => '',
            'token' => '',
            'balance' => '',
            'transactionId' => $data['transactionId'],
            'currency' => $this->currency,
            'bonusAmount' => '0',
            'errorCode' => '0',
            'errorDescription' => 'Completed successfully',
            'timestamp' => self::GetTime(),
        ];

        if ($token_data = self::IsTokenValid($data['token'], 0)) {
            if ($user = self::GetUserInfo($token_data['user_id'])) {
                $new_balance = $user['balance'] - $data['debitAmount'];

                $response['uid'] = $token_data['user_id'];
                $response['token'] = $data['token'];
                $response['balance'] = $new_balance;

                if (! self::IsRollback($data['transactionId'])) {
                    if ($new_balance >= 0) {
                        if ($this->GetTransaction('debit', $data['transactionId']) or $this->GetTransaction('credit', $data['transactionId'])) {
                            $response['errorCode'] = '0';
                            $response['errorDescription'] = 'Transaction already exists';
                            $response['balance'] = $user['balance'];
                        } else {
                            PDO_wrap::$pdo->beginTransaction();
                            $this->SetUserBalance($token_data['user_id'], $new_balance, -1 * $data['debitAmount']);
                            $this->SaveTransaction('debit', $data, $token_data, $new_balance, -1 * $data['debitAmount']);
                            $user = self::GetUserInfo($token_data['user_id']);
                            $response['balance'] = $user['balance'];

                            if ($response['balance'] >= 0) {
                                PDO_wrap::$pdo->commit();
                            } else {
                                PDO_wrap::$pdo->rollBack();
                                $user = self::GetUserInfo($token_data['user_id']);
                                $response['balance'] = $user['balance'];
                                $response['errorCode'] = '3';
                                $response['errorDescription'] = 'Insufficient funds';
                            }
                        }
                    } else {
                        $response['errorCode'] = '3';
                        $response['errorDescription'] = 'Insufficient funds';
                        $response['balance'] = $user['balance'];
                    }
                } else {
                    $response['errorCode'] = '10';
                    $response['errorDescription'] = 'Transaction timed out';
                    $response['balance'] = $user['balance'];
                }
            } else {
                $response['errorCode'] = '7';
                $response['errorDescription'] = 'User not found';
            }
        } else {
            $response['errorCode'] = '6';
            $response['errorDescription'] = 'Token not found';
        }

        return $response;
    }

    public function Credit($data)
    {
        if ($data['operatorId'] != $this->operator_id) {
            throw new Exception('Invalid operator');
        }
        if (empty($data['transactionId'])) {
            throw new Exception('Transaction not found');
        }
        if (! is_numeric($data['creditAmount']) or $data['creditAmount'] < 0) {
            throw new Exception('Invalid amount');
        }

        $response = [
            'operatorId' => $this->operator_id,
            'roundId' => $data['roundId'],
            'uid' => '',
            'token' => '',
            'balance' => '',
            'transactionId' => $data['transactionId'],
            'currency' => $this->currency,
            'bonusAmount' => '0',
            'errorCode' => '0',
            'errorDescription' => 'Completed successfully',
            'timestamp' => self::GetTime(),
        ];

        if ($token_data = self::IsTokenValid($data['token'], 0)) {
            if ($user = self::GetUserInfo($token_data['user_id'])) {
                $new_balance = $user['balance'] + $data['creditAmount'];

                $response['uid'] = $token_data['user_id'];
                $response['token'] = $data['token'];
                $response['balance'] = $new_balance;

                if (! $this->GetTransaction('credit', $data['transactionId'])) {
                    $this->SetUserBalance($token_data['user_id'], $new_balance, $data['creditAmount']);
                    $this->SaveTransaction('credit', $data, $token_data, $new_balance, $data['creditAmount']);
                    $user = self::GetUserInfo($token_data['user_id']);
                    $response['balance'] = $user['balance'];
                } else {
                    $response['balance'] = $user['balance'];
                    $response['errorCode'] = '0';
                    $response['errorDescription'] = 'Transaction already exists';
                }
            } else {
                $response['errorCode'] = '7';
                $response['errorDescription'] = 'User not found';
            }
        } else {
            $response['errorCode'] = '6';
            $response['errorDescription'] = 'Token not found';
        }

        return $response;
    }

    public function Rollback($data)
    {
        if ($data['operatorId'] != $this->operator_id) {
            throw new Exception('Invalid operator');
        }
        if (empty($data['transactionId'])) {
            throw new Exception('Transaction not found');
        }
        if (empty($data['rollbackAmount']) or $data['rollbackAmount'] < 0) {
            throw new Exception('Invalid amount');
        }

        $response = [
            'operatorId' => $this->operator_id,
            'roundId' => $data['roundId'],
            'uid' => '',
            'token' => '',
            'balance' => '',
            'transactionId' => $data['transactionId'],
            'currency' => $this->currency,
            'bonusAmount' => '0',
            'errorCode' => '0',
            'errorDescription' => 'Completed successfully',
            'timestamp' => self::GetTime(),
        ];

        if ($token_data = self::IsTokenValid($data['token'], 0)) {
            if ($user = self::GetUserInfo($token_data['user_id'])) {
                self::AddRollback($data['transactionId']);
                $new_balance = $user['balance'] + $data['rollbackAmount'];
                $response['uid'] = $token_data['user_id'];
                $response['token'] = $data['token'];
                $response['balance'] = $new_balance;

                if ($transaction = $this->GetTransaction('debit', $data['transactionId'])) {
                    $this->SetUserBalance($token_data['user_id'], $new_balance, $data['rollbackAmount']);

                    PDO_wrap::Change('DELETE FROM casino_transactions WHERE user_id=:user_id AND transactionId=:transactionId', [
                        'transactionId' => $data['transactionId'],
                        'user_id' => $user['id'],
                    ]);

                    $user = self::GetUserInfo($token_data['user_id']);
                    $response['balance'] = $user['balance'];
                } else {
                    $response['errorCode'] = '9';
                    $response['errorDescription'] = 'Transaction not found';
                    $response['balance'] = $user['balance'];
                }
            } else {
                $response['errorCode'] = '7';
                $response['errorDescription'] = 'User not found';
            }
        } else {
            $response['errorCode'] = '6';
            $response['errorDescription'] = 'Token not found';
        }

        return $response;
    }

    public static function IsRollback($id)
    {
        $res = PDO_wrap::getArray('SELECT * FROM casino_rollback_ids WHERE rollback_id=:rollback_id LIMIT 1', ['rollback_id' => $id]);

        if (count($res) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function AddRollback($id)
    {
        PDO_wrap::Change('INSERT INTO casino_rollback_ids(rollback_id) VALUES(:rollback_id)', ['rollback_id' => $id]);
    }

    public function GetToken($is_temp, $user_id = false, $client_ip = false)
    {
        if (! $user_id) {
            $user_id = $this->GetUserId();
        }
        if (! $client_ip) {
            $client_ip = self::GetIP();
        }

        if ($is_temp) {
            $expired = 30;
        } else {
            $expired = 3600 * 24 * 7;
        }

        for ($i = 0; $i < 100; $i = $i + 1) {
            $token = md5($user_id.time().microtime()).uniqid();

            PDO_wrap::Change('DELETE FROM casino_tokens WHERE user_id=:user_id', ['user_id' => $user_id]);

            PDO_wrap::Change('INSERT INTO casino_tokens(user_id, token, client_ip, create_time, expired_time, is_temp) VALUES(:user_id, :token, :client_ip, :create_time, :expired_time, :is_temp)', [
                'user_id' => $user_id,
                'token' => $token,
                'client_ip' => $client_ip,
                'create_time' => time(),
                'expired_time' => time() + $expired,
                'is_temp' => $is_temp,
            ]);

            return $token;
        }
    }

    public static function GetIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            }

            return $_SERVER['REMOTE_ADDR'];
        }

        if (getenv('HTTP_X_FORWARDED_FOR')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        }

        if (getenv('HTTP_CLIENT_IP')) {
            return getenv('HTTP_CLIENT_IP');
        }

        return getenv('REMOTE_ADDR');
    }
}
