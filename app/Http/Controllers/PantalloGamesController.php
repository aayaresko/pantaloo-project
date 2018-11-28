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
                    $amount = (float)$requestParams['amount'];
                    $externalTransactionId = $requestParams['transaction_id'];
                    $roundId = $requestParams['round_id'];
                    //if existing two transaction then return response how respond docs

                    //create transaction own and external
                    //update user balance +
                    $transaction = GamesPantalloTransaction::leftJoin('transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId]
                        ])->select([
                            'transactions.id',
                            'games_pantallo_transactions.balance as current_balance'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
                        $transaction = Transaction::create([
                            'comment' => 'Pantallo games',
                            'sum' => $amount,
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId
                        ]);

                        //edit balance user
                        $balance = (float)$params['user']->balance - (float)$amount;

                        User::where('id', $params['user']->id)->update([
                            //'balance' => DB::raw("balance+{$amount}")
                            'balance' => $balance
                        ]);

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'balance' => $balance
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                    } else {
                        $balance = $transaction->current_balance;
                    }
                    $response = [
                        'status' => 200,
                        'balance' => $balance
                    ];

                    break;
                case 'credit':
                    $amount = (float)$requestParams['amount'];
                    $externalTransactionId = $requestParams['transaction_id'];
                    $roundId = $requestParams['round_id'];
                    //if existing two transaction then return response how respond docs

                    //create transaction own and external
                    //update user balance +
                    $transaction = GamesPantalloTransaction::leftJoin('transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId]
                        ])->select([
                            'transactions.id',
                            'games_pantallo_transactions.balance as current_balance'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
                        $transaction = Transaction::create([
                            'comment' => 'Pantallo games',
                            'sum' => $amount,
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId
                        ]);

                        //edit balance user
                        $balance = (float)$params['user']->balance + (float)$amount;

                        User::where('id', $params['user']->id)->update([
                            //'balance' => DB::raw("balance+{$amount}")
                            'balance' => $balance
                        ]);

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'balance' => $balance
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                    } else {
                        $balance = $transaction->current_balance;
                    }
                    $response = [
                        'status' => 200,
                        'balance' => $balance
                    ];
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
            dd($e->getMessage());
            $response = [
                'status' => 500,
                'msg' => $e->getMessage()
            ];
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