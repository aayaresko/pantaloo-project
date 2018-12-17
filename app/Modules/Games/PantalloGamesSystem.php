<?php

namespace App\Modules\Games;

use DB;
use Log;
use Validator;
use App\RawLog;
use App\User;
use App\Transaction;
use App\Models\GamesList;
use Illuminate\Http\Request;
use App\Modules\PantalloGames;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloTransaction;
use App\Models\Pantallo\GamesPantalloSessionGame;

/**
 * Class PantalloGamesSystem
 * @package App\Modules\Games
 */
class PantalloGamesSystem implements GamesSystem
{
    /**
     * Why constant - in doc for integration write such make
     */
    const PASSWORD = 'rf3js1Q';

    /**
     * @param $request
     * @return array|mixed
     */
    public function loginPlayer($request)
    {
        DB::beginTransaction();
        try {
            $game = GamesList::where('id', $request->gameId)->first();
            $gameId = $game->system_id;
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
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        DB::commit();
        return [
            'success' => true,
            'message' => [
                'gameLink' => $getGame->response
            ]
        ];
    }

    /**
     * @param $user
     * @return array|mixed
     */
    public function logoutPlayer($user)
    {
        DB::beginTransaction();
        try {
            $configCommon = config('integratedGames.common');
            $statusLogout = $configCommon['statusSession']['logout'];
            $statusLogin = $configCommon['statusSession']['login'];
            $pantalloGames = new PantalloGames;
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
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        DB::commit();
        return [
            'success' => true
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function callback($request)
    {
        $start = microtime(true);
        $startTime = explode(" ", microtime());
        $startDate = date("m-d-y H:i:s", $startTime[1]) . substr((string)$startTime[0], 1, 4);

        DB::beginTransaction();
        try {
            //validation
            $params = [];
            $requestParams = $request->query();
            Log::info($requestParams);

            $configPantalloGames = config('pantalloGames');
            $salt = $configPantalloGames['additional']['salt'];
            $typesActions = $configPantalloGames['additional']['action'];
            $typesOperation = $configPantalloGames['additional']['operation'];
            $accuracyValues = config('app.accuracyValues');
            $validationDate = $requestParams;
            $key = $validationDate['key'];
            unset($validationDate['key']);
            $hash = sha1($salt . http_build_query($validationDate));

            if ($key != $hash) {
                throw new \Exception('Incorrect input date');
            }
            //end validation

            //action

            //get user for this session
            $params['session'] = GamesPantalloSession::where('sessionid', $requestParams['session_id'])->first();
            if (is_null($params['session'])) {
                throw new \Exception('Session is not found');
            }

            $params['user'] = User::where('id', $params['session']->user_id)->first();
            if (is_null($params['session'])) {
                throw new \Exception('User is not found');
            }

            $balanceBefore = (float)$params['user']->balance;
            if ((float)$params['user']->balance < 0) {
                throw new \Exception('Insufficient funds', 403);
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
                    $caseAction = 'debit';
                    $amount = (float)$requestParams['amount'];
                    $externalTransactionId = $requestParams['transaction_id'];
                    $roundId = isset($requestParams['round_id']) ? $requestParams['round_id'] : null;
                    //if existing two transaction then return response how respond docs

                    //create transaction own and external
                    //update user balance +
                    $transaction = Transaction::leftJoin('games_pantallo_transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId],
                            ['games_pantallo_transactions.action_id', '=', $typesActions[$caseAction]]
                        ])->select([
                            'transactions.id',
                            'games_pantallo_transactions.balance_before as balance_before',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
                        if ((float)$amount > $params['user']->balance) {
                            throw new \Exception('Insufficient funds', 403);
                        }

                        $typeId = 1;
                        $transaction = Transaction::create([
                            'type' => $typeId,
                            'comment' => 'Pantallo games',
                            'sum' => $amount,
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId
                        ]);

                        //edit balance user
                        $balance = $typesOperation[$caseAction]((float)$params['user']->balance,
                            (float)$amount, $accuracyValues);

                        User::where('id', $params['user']->id)->update([
                            //'balance' => DB::raw("balance+{$amount}")
                            'balance' => $balance
                        ]);

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'balance_before' => $params['user']->balance,
                            'balance_after' => $balance,
                            'action_id' => $typesActions[$caseAction]
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                    } else {
                        $balance = $transaction->balance_after;
                    }
                    $response = [
                        'status' => 200,
                        'balance' => (float)$balance
                    ];
                    break;
                case 'credit':
                    $caseAction = 'credit';
                    $amount = (float)$requestParams['amount'];
                    $externalTransactionId = $requestParams['transaction_id'];
                    $roundId = isset($requestParams['round_id']) ? $requestParams['round_id'] : null;
                    //if existing two transaction then return response how respond docs

                    //create transaction own and external
                    //update user balance +
                    $transaction = Transaction::leftJoin('games_pantallo_transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId],
                            ['games_pantallo_transactions.action_id', '=', $typesActions[$caseAction]]
                        ])->select([
                            'transactions.id',
                            'games_pantallo_transactions.balance_before as balance_before',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
//                        if ((float)$amount > $params['user']->balance) {
//                            throw new \Exception('Insufficient funds', 403);
//                        }
                        $typeId = 2;
                        $transaction = Transaction::create([
                            'type' => $typeId,
                            'comment' => 'Pantallo games',
                            'sum' => $amount,
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId
                        ]);

                        //edit balance user
                        $balance = $typesOperation[$caseAction]((float)$params['user']->balance,
                            (float)$amount, $accuracyValues);

                        User::where('id', $params['user']->id)->update([
                            //'balance' => DB::raw("balance+{$amount}")
                            'balance' => $balance
                        ]);

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'balance_before' => $params['user']->balance,
                            'balance_after' => $balance,
                            'action_id' => $typesActions[$caseAction]
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                    } else {
                        $balance = $transaction->balance_after;
                    }
                    $response = [
                        'status' => 200,
                        'balance' => (float)$balance
                    ];
                    break;
                case 'rollback':
                    $caseAction = 'rollback';
                    $externalTransactionId = $requestParams['transaction_id'];
                    $roundId = isset($requestParams['round_id']) ? $requestParams['round_id'] : null;
                    //if existing two transaction then return response how respond docs

                    //create transaction own and external
                    //update user balance +
                    $transactionHas = Transaction::leftJoin('games_pantallo_transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId],
                            ['games_pantallo_transactions.action_id', '<>', $typesActions[$caseAction]]
                        ])->select([
                            'transactions.id',
                            'action_id',
                            'games_pantallo_transactions.balance_before as balance_before',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    $diffBalance = bcsub((float)$transactionHas->balance_after,
                        (float)$transactionHas->balance_before, $accuracyValues);
                    $amount = abs($diffBalance);

                    if (is_null($transactionHas)) {
                        throw new \Exception('Does not have a transaction', 404);
                    }

                    $transaction = Transaction::leftJoin('games_pantallo_transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId],
                            ['games_pantallo_transactions.action_id', '=', $typesActions[$caseAction]]
                        ])->select([
                            'transactions.id',
                            'games_pantallo_transactions.balance_before as balance_before',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
                        $currentOperation = array_search($transactionHas->action_id, $typesActions);

                        //edit balance user
                        $currentOperation = ($currentOperation === 'debit') ? 'credit' : 'debit';
                        $balance = $typesOperation[$currentOperation]((float)$params['user']->balance, (float)$amount);

                        //CHECK THIS = ASK MAX
                        if ($balance < 0) {
                            throw new \Exception('Insufficient funds', 403);
                        }

                        $typeId = ($currentOperation === 'debit') ? 2 : 1;
                        $transaction = Transaction::create([
                            'type' => $typeId,
                            'comment' => 'Pantallo games',
                            'sum' => $amount,
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId
                        ]);

                        User::where('id', $params['user']->id)->update([
                            //'balance' => DB::raw("balance+{$amount}")
                            'balance' => $balance
                        ]);

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'balance_before' => $params['user']->balance,
                            'balance_after' => $balance,
                            'action_id' => $typesActions[$caseAction]
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                    } else {
                        $balance = $transaction->balance_after;
                    }
                    $response = [
                        'status' => 200,
                        'balance' => (float)$balance
                    ];
                    break;
                default:
                    throw new \Exception('Action is not found');
            }
            //create add database for pantallo games only
            //time counter
            $endTime = explode(" ", microtime());
            $endDate = date("m-d-y H:i:s", $endTime[1]) . substr((string)$endTime[0], 1, 4);
            $time = round(microtime(true) - $start, 4);
            $responseLog = $response;
            $responseLog['time'] = $time;
            $responseLog['startDate'] = $startDate;
            $responseLog['endDate'] = $endDate;

            RawLog::create([
                'request' => json_encode($requestParams),
                'response' => json_encode($responseLog)
            ]);
        } catch (\Exception $e) {
            //dd($e);
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();
            DB::rollBack();
            $response = [
                'status' => 500,
                'msg' => $errorMessage . ' Line:' . $errorLine
            ];

            if ($errorCode) {
                $response['status'] = $errorCode;
                $response['balance'] = $balanceBefore;
            }
        }
        DB::commit();
        return $response;
    }
}