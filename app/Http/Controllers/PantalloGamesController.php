<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\RawLog;
use App\User;
use App\Transaction;
use App\Http\Requests;
use App\Models\GamesList;
use Illuminate\Http\Request;
use App\Modules\PantalloGames;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloTransaction;
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
        dd($request->toArray());
        DB::beginTransaction();
        try {
            //validation
            $params = [];
            $requestParams = $request->query();

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
                    $response = [
                        'status' => 200,
                        'balance' => (float)$params['user']->balance
                    ];
                    break;
                case 'debit':
                    $amount = $requestParams['amount'];
                    $externalTransactionId = $requestParams['transaction_id'];
                    $roundId = $requestParams['round_id'];

                    //if existing two transaction then return response how respond docs

                    //create transaction own and external
                    //update user balance +
                    GamesPantalloTransaction::where('')->first();

                    $resp['roundId'] = $round_id;
                    $resp['token'] = $token->token;
                    $resp['balance'] = $user->getRealBalance();
                    $resp['currency'] = 'mBTC';
                    $resp['transactionId'] = $ext_id;

                    $rollback = Rollback::where('ext_id', $ext_id)->first();
                    if($rollback) throw new \Exception('Debit after rollback / General Error', 1);

                    if(!is_numeric($round_id)) throw new \Exception('General error', 1);

                    if(!is_numeric($sum)) throw new \Exception('General error', 1);
                    if($sum < 0) throw new \Exception('General error', 1);

                    if(empty($ext_id)) throw new \Exception('General error', 1);

                    $transaction = Transaction::where('ext_id', $ext_id)->first();

                    if($transaction) throw new \Exception('Transaction has already processed', 0);

                    if((string)$input['uid'] != (string)$user->id) throw new \Exception('User not found', 7);

                    $transaction = new Transaction();
                    $transaction->ext_id = $ext_id;
                    $transaction->type = 1;
                    $transaction->user()->associate($user);
                    $transaction->token()->associate($token);
                    $transaction->round_id = $round_id;
                    $transaction->bonus_sum = 0;

                    if($user->balance < $sum)
                    {
                        throw new \Exception('Insufficient funds', 3);
                    }

                    $transaction->sum = -1*$sum;

                    try {
                        $user->changeBalance($transaction);
                    }
                    catch (\Exception $e)
                    {
                        throw new \Exception('Insufficient funds', 3);
                    }

                    $balance = $user->getRealBalance();

                    $resp['balance'] = $balance;

                    $resp['errorCode'] = 0;
                    $resp['errorDescription'] = 'OK';
                    $resp['timestamp'] = Ezugi::GetTime();
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
            RawLog::create([
                'request' => json_encode($requestParams),
                'response' => json_encode($response)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 500,
                'balance' => 0,
                'msg' => $e->getMessage()
            ];
            dd($e->getMessage());
        }
        DB::commit();

        return response()->json($response);
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