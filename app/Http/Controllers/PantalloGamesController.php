<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\RawLog;
use App\User;
use App\Http\Requests;
use App\Models\GamesList;
use Illuminate\Http\Request;
use App\Modules\PantalloGames;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloSessionGame;

class PantalloGamesController extends Controller
{
    /**
     * Why constant - in doc for integration write such make
     */
    const PASSWORD = 'rf3js1Q';

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function endpoint(Request $request)
    {
        try {
            //validation
            $params = [];
            $requestParams = $request->query();

            $validator = Validator::make($requestParams, [
                'callerId' => 'required|string',
                'callerPassword' => 'required|string',
                'callerPrefix' => 'required|string',
                'username' => 'required|string',

                'action' => 'required|string',
                'remote_id' => 'required|integer',
                'game_id' => 'required|string',
                'session_id' => 'required',
                'key' => 'required|string',
                'gamesession_id' => 'required|string',
                'game_id_hash' => 'string',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Problem with validation');
            }

            $configPantalloGames = config('pantalloGames');
            $salt = $configPantalloGames['additional']['salt'];
            $validationDate = $requestParams;
            $key = $validationDate['key'];
            unset($validationDate['key']);
            $hash = sha1($salt . http_build_query($validationDate));

            if ($key != $hash) {
                throw new \Exception('Incorrect input date');
            }
            //end validation
            //action
            Log::info($requestParams);
            $raw_log = RawLog::create(['request' => json_encode($requestParams)]);
            //get user for this session
            $params['session'] = GamesPantalloSession::where('sessionid', $requestParams['session_id'])->first();
            if (is_null($params['session'])) {
                throw new \Exception('Session is not found');
            }

            $params['user'] = User::where('id', $params['session']->user_id)->first();
            if (is_null($params['session'])) {
                throw new \Exception('User is not found');
            }

            $action = $requestParams['action'];

            switch ($action) {
                case 'balance':
                    dd(2);
                    break;
                case 'debit':
                    dd(2);
                    break;
                case 'credit':
                    dd(2);
                    break;
                case 'rollback':
                    dd(2);
                    break;
                default:
                    throw new \Exception('Action is not found');
            }


        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        dd(2);


        try {
            if ($input['operatorId'] != env('EZUGI_OPERATOR_ID')) throw new \Exception('General error', 1);
            //if(!isset($input['request'])) throw new \Exception('General error', 1);

            //if($request->ip() != '46.4.63.60') throw new \Exception('Technical error', 1);

            $resp = [
                'operatorId' => env('EZUGI_OPERATOR_ID'),
                'timestamp' => Ezugi::GetTime()
            ];

            if ($request->input('request') == 'auth') {
                $public_token = PublicToken::where('token', $input['token'])->first();
                //if(!$token) $token = Token::where('token', $request->input('gamesessionid'))->first();
                if (!$public_token) throw new \Exception('Token not found', 6);

                $user = $public_token->user;

                if (!$user) throw new \Exception('User not found', 7);
            } else {
                $token = Token::where('token', $input['token'])->first();
                //if(!$token) $token = Token::where('token', $request->input('gamesessionid'))->first();
                if (!$token) throw new \Exception('Token not found', 6);

                $user = $token->user;

                if (!$user) throw new \Exception('User not found', 7);
            }

            $resp['uid'] = (string)$user->id;

            switch ($request->input('request')) {
                case 'auth':

                    if ($user->id == 25) $private_token = '58c118028c36c468776592-4e8e57ab0d';
                    else {
                        $token = $public_token->getToken();
                        $private_token = $token->token;
                    }

                    $resp = [
                        'operatorId' => env('EZUGI_OPERATOR_ID'),
                        'uid' => (string)$user->id,
                        'nickName' => preg_replace('|[^A-z0-9]*|isUS', '', preg_replace('|@.*$|isUS', '', $user->email)),
                        'token' => $private_token,
                        'playerTokenAtLaunch' => $public_token->token,
                        'balance' => $user->getRealBalance(),
                        'currency' => 'mBTC',
                        'language' => 'en',
                        'errorCode' => 0,
                        'errorDescription' => 'OK',
                        'timestamp' => Ezugi::GetTime(),
                        'clientIP' => '127.0.0.1'
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
                    if ($rollback) throw new \Exception('Debit after rollback / General Error', 1);

                    if (!is_numeric($round_id)) throw new \Exception('General error', 1);

                    if (!is_numeric($sum)) throw new \Exception('General error', 1);
                    if ($sum < 0) throw new \Exception('General error', 1);

                    if (empty($ext_id)) throw new \Exception('General error', 1);

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if ($transaction) throw new \Exception('Transaction has already processed', 0);

                    if ((string)$input['uid'] != (string)$user->id) throw new \Exception('User not found', 7);

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

                    if (!$round_id) throw new \Exception('General error', 1);
                    if (!is_numeric($sum)) throw new \Exception('General error', 1);
                    if ($sum < 0) throw new \Exception('General error', 1);

                    if (empty($ext_id)) throw new \Exception('General error', 1);

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if ($transaction) throw new \Exception('Transaction has already processed', 0);

                    if ((string)$input['uid'] != (string)$user->id) throw new \Exception('User not found', 7);

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

                    if ((string)$input['uid'] != (string)$user->id) throw new \Exception('User not found', 7);

                    if (!$round_id) throw new \Exception('General error', 1);
                    if (!is_numeric($sum)) throw new \Exception('General error', 1);
                    if ($sum < 0) throw new \Exception('General error', 1);

                    if (empty($ext_id)) throw new \Exception('General error', 1);

                    $rollback = Rollback::where('ext_id', $ext_id)->first();

                    if (!$rollback) {
                        $rollback = new Rollback();
                        $rollback->ext_id = $ext_id;
                        $rollback->save();
                    }

                    $transaction = Transaction::withTrashed()->where('ext_id', $ext_id)->first();

                    if (!$transaction) throw new \Exception('Transaction not found', 9);
                    elseif ($transaction->trashed()) throw new \Exception('Transaction has already processed', 0);

                    if ($transaction->type != 1) throw new \Exception('General error', 1);
                    if ($transaction->sum != -1 * $sum) throw new \Exception('General error', 1);
                    if ($user->id != $transaction->user_id) throw new \Exception('General error', 1);

                    $user->changeBalance($transaction, true);

                    $balance = $user->getRealBalance();

                    $resp['errorCode'] = 0;
                    $resp['errorDescription'] = 'OK';
                    $resp['timestamp'] = Ezugi::GetTime();
                    $resp['balance'] = $balance;

                    break;
                default:
                    throw new \Exception('No such method');
            }
        } catch (\Exception $e) {
            $code = $e->getCode();
            if (!$code) $code = 1;

            $resp['errorCode'] = $e->getCode();
            $resp['errorDescription'] = $e->getMessage();

            $raw_log->response = $e->getTraceAsString();
            $raw_log->save();

            return response()->json($resp);
        }

        $raw_log->response = json_encode($resp);
        $raw_log->save();

        return response()->json($resp);


        return response()->json([
            'status' => 200,
            'balance' => 100
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getGameList(Request $request)
    {
        $pantalloGames = new PantalloGames;
        $params = [];
        $games = $pantalloGames->getGameList($params, true);
        return count($games->response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginPlayer(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'gameId' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        DB::beginTransaction();
        try {
            $game = GamesList::where('id', $request->gameId)->first();
            $gameId = $game->id;
            $user = $request->user();
            $userId = $user->id;
            $pantalloGames = new PantalloGames;
            $playerExists = $pantalloGames->playerExists([
                'user_username' => $user->id,
            ], true);

            //active player request
            if ($playerExists->response === false) {
                $player = $pantalloGames->createPlayer([
                    'user_id' => $userId,
                    'user_username' => $userId,
                    'password' => self::PASSWORD
                ], true);
            } else {
                $player = $playerExists;
            }

            //login request
            $login = $pantalloGames->loginPlayer([
                'user_id' => $userId,
                'user_username' => $userId,
                'password' => self::PASSWORD
            ], true);
            $loginResponse = (array)$login->response;
            $idLogin = $loginResponse['id'];
            unset($loginResponse['id']);
            $loginResponse['system_id'] = $idLogin;
            $loginResponse['user_id'] = $userId;

            GamesPantalloSession::updateOrCreate(
                ['sessionid' => $loginResponse['sessionid']], $loginResponse);
            //get games
            $getGame = $pantalloGames->getGame([
                'lang' => 'en',
                'user_id' => $user->id,
                'user_username' => $user->id,
                'user_password' => self::PASSWORD,
                'gameid' => $gameId,
                'play_for_fun' => 0,
                'homeurl' => url(''),
            ], true);

            GamesPantalloSessionGame::create(['session_id' => $idLogin,
                'gamesession_id' => $getGame->gamesession_id]);

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        DB::commit();
        return response()->json([
            'success' => false,
            'message' => [
                'gameLink' => $getGame->response
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutPlayer(Request $request)
    {
        DB::beginTransaction();
        try {
            $configCommon = config('integratedGames.common');
            $statusLogout = $configCommon['statusSession']['logout'];
            $statusLogin = $configCommon['statusSession']['login'];
            $pantalloGames = new PantalloGames;
            $user = $request->user();
            $userId = $user->id;
            $logout = $pantalloGames->logoutPlayer([
                'user_id' => $userId,
                'user_username' => $userId,
                'password' => self::PASSWORD
            ], true);
            $session = GamesPantalloSession::where([
                ['user_id', '=', $user->id],
                ['status', '<>', $statusLogout],
            ])->first();
            $session->status = 1;
            $session->save();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        DB::commit();
        return response()->json([
            'success' => true
        ]);
    }
}