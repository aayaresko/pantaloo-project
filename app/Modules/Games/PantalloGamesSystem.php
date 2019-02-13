<?php

namespace App\Modules\Games;

use DB;
use Log;
use App\User;
use Symfony\Component\Console\Helper\Helper;
use Validator;
use App\RawLog;
use App\Transaction;
use App\Models\GamesList;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Modules\PantalloGames;
use App\Modules\Others\DebugGame;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloFreeRounds;
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
        $debugGame = new DebugGame();
        $debugGame->start();

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
            dump($playerExists);
            dump($player);
            dump('login');
            dump($login);
            dump('getGame');
            dump($getGame);
            dump($e);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        DB::commit();

        //finish debug
        $response = [
            'success' => true,
            'message' => [
                'gameLink' => $getGame->response
            ]
        ];
        $debugGameResult = $debugGame->end();

        RawLog::create([
            'type_id' => 1,
            'request' => GeneralHelper::fullRequest(),
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult)
        ]);

        return $response;
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
        $debugGame = new DebugGame();
        $debugGame->start();

        DB::beginTransaction();
        try {
            //validation
            $modePlay = 0; //play real money
            $params = [];
            $requestParams = $request->query();
            Log::info($requestParams);

            $configPantalloGames = config('pantalloGames');
            $salt = $configPantalloGames['additional']['salt'];
            $typesActions = $configPantalloGames['additional']['action'];

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

            $userFields = [
                'users.id as id',
                'users.balance as balance',
                'users.bonus_balance as bonus_balance',
                DB::raw('(users.balance + users.bonus_balance) as full_balance'),
                'affiliates.id as partner_id',
                'affiliates.commission as partner_commission',
            ];

            //add additional fields
            $additionalFieldsUser = [
                'affiliates.id as partner_id',
                'affiliates.commission as partner_commission',
            ];

            $params['user'] = User::select(array_merge($userFields, $additionalFieldsUser))
                ->leftJoin('users as affiliates', 'users.agent_id', '=', 'affiliates.id')
                ->where('users.id', $params['session']->user_id)->first();

            if (is_null($params['session'])) {
                throw new \Exception('User is not found');
            }

            $params['game'] = GamesList::select(['id', 'system_id'])
                ->where('system_id', $requestParams['game_id'])->first();
            if (is_null($params['game'])) {
                throw new \Exception('Game is not found');
            }

            $balanceBefore = GeneralHelper::formatAmount($params['user']->full_balance);

            if ($balanceBefore < 0) {
                throw new \Exception('Insufficient funds', 403);
            }

            if (!is_null($params['user']->bonus) and (float)$params['user']->balance < 0) {
                $modePlay = 1;//mode play bonus money
            }
            $action = $requestParams['action'];

            switch ($action) {
                case 'balance':
                    $response = [
                        'status' => 200,
                        'balance' => $balanceBefore
                    ];
                    break;
                case 'debit':
                    $caseAction = 'debit';

                    //our valdiate
                    $validator = Validator::make($request->all(), [
                        'amount' => 'required|numeric|min:0',
                    ]);

                    if ($validator->fails()) {
                        $error = $validator->errors();
                        throw new \Exception($error->first(), 500);
                    }
                    //end our validate

                    $amount = (-1) * GeneralHelper::formatAmount($requestParams['amount']);
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
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
                        if (abs($amount) > $balanceBefore) {
                            throw new \Exception('Insufficient funds', 403);
                        }

                        $typeId = 1;
                        $partnerId = $params['user']->partner_id;
                        $partnerCommission = $params['user']->partner_commission;
                        $createParams = [
                            'type' => $typeId,
                            'comment' => 'Pantallo games',
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId,
                            'agent_id' => (!is_null($partnerId)) ? $partnerId : 0,
                            'agent_commission' => (!is_null($partnerCommission)) ? $partnerCommission : 0,
                        ];

                        if ($modePlay === 0) {
                            $createParams['sum'] = $amount;
                        } else {
                            $createParams['bonus_sum'] = $amount;
                        }

                        $transaction = Transaction::create($createParams);

                        //edit balance user
                        $updateBalance = [];
                        if ($modePlay === 0) {
                            $updateUser['balance'] = DB::raw("balance+$amount");
                        } else {
                            $updateUser['bonus_balance'] = DB::raw("bonus_balance+$amount");
                        }

                        User::where('id', $params['user']->id)
                            ->update($updateBalance);

                        $userAfterUpdate = User::select($userFields)->where('id', $params['user']->id)->first();
                        $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->full_balance);

                        if ($userAfterUpdate->bonus_balance < 0
                            or $userAfterUpdate->balance < 0
                            or $balanceAfterTransaction < 0) {
                            throw new \Exception('Insufficient funds', 403);
                        }

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'action_id' => $typesActions[$caseAction],
                            'amount' => $amount,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $balanceAfterTransaction,
                            'game_id' => $params['game']->id
                        ];

                        GamesPantalloTransaction::create($pantalloTransaction);
                        $balance = $balanceAfterTransaction;
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

                    //our valdiate
                    $validator = Validator::make($request->all(), [
                        'amount' => 'required|numeric|min:0',
                    ]);

                    if ($validator->fails()) {
                        $error = $validator->errors();
                        throw new \Exception($error->first(), 500);
                    }
                    //end our validate

                    $amount = GeneralHelper::formatAmount($requestParams['amount']);
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
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
//                        if ((float)$amount > $params['user']->balance) {
//                            throw new \Exception('Insufficient funds', 403);
//                        }
                        $typeId = 2;
                        $partnerId = $params['user']->partner_id;
                        $partnerCommission = $params['user']->partner_commission;
                        $createParams = [
                            'type' => $typeId,
                            'comment' => 'Pantallo games',
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId,
                            'agent_id' => (!is_null($partnerId)) ? $partnerId : 0,
                            'agent_commission' => (!is_null($partnerCommission)) ? $partnerCommission : 0,
                        ];

                        if ($modePlay === 0) {
                            $createParams['sum'] = $amount;
                        } else {
                            $createParams['bonus_sum'] = $amount;
                        }

                        $transaction = Transaction::create($createParams);

                        //edit balance user
                        $updateBalance = [];
                        if ($modePlay === 0) {
                            $updateUser['balance'] = DB::raw("balance+$amount");
                        } else {
                            $updateUser['bonus_balance'] = DB::raw("bonus_balance+$amount");
                        }

                        User::where('id', $params['user']->id)
                            ->update($updateBalance);

                        $userAfterUpdate = User::select($userFields)->where('id', $params['user']->id)->first();
                        $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->full_balance);

                        if ($userAfterUpdate->bonus_balance < 0
                            or $userAfterUpdate->balance < 0
                            or $balanceAfterTransaction < 0) {
                            throw new \Exception('Insufficient funds', 403);
                        }

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'action_id' => $typesActions[$caseAction],
                            'amount' => $amount,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $balanceAfterTransaction,
                            'game_id' => $params['game']->id
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                        $balance = $balanceAfterTransaction;
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
                            'games_pantallo_transactions.amount as amount',
                            'games_pantallo_transactions.game_id as game_id',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transactionHas)) {
                        throw new \Exception('Does not have a transaction', 404);
                    }

                    if ($transactionHas->action_id === 1) {
                        $amountTransaction = (float)$transactionHas->amount;
                    } else {
                        //CHECK THIS = ASK MAX
                        if ((float)$transactionHas->amount > $balanceBefore) {
                            throw new \Exception('Insufficient funds', 403);
                        }
                        $amountTransaction = (-1) * (float)$transactionHas->amount;
                    }
                    $amount = GeneralHelper::formatAmount($amountTransaction);

                    $transaction = Transaction::leftJoin('games_pantallo_transactions',
                        'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                        ->where([
                            ['system_id', '=', $externalTransactionId],
                            ['games_pantallo_transactions.action_id', '=', $typesActions[$caseAction]]
                        ])->select([
                            'transactions.id',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transaction)) {
                        //create and return value
                        $currentOperation = array_search($transactionHas->action_id, $typesActions);
                        //edit balance user
                        $currentOperation = ($currentOperation === 'debit') ? 'credit' : 'debit';

                        $typeId = ($currentOperation === 'debit') ? 2 : 1;
                        $partnerId = $params['user']->partner_id;
                        $partnerCommission = $params['user']->partner_commission;
                        $createParams = [
                            'type' => $typeId,
                            'comment' => 'Pantallo games',
                            'user_id' => $params['user']->id,
                            'round_id' => $roundId,
                            'agent_id' => (!is_null($partnerId)) ? $partnerId : 0,
                            'agent_commission' => (!is_null($partnerCommission)) ? $partnerCommission : 0,
                        ];

                        if ($modePlay === 0) {
                            $createParams['sum'] = $amount;
                        } else {
                            $createParams['bonus_sum'] = $amount;
                        }

                        $transaction = Transaction::create($createParams);

                        //edit balance user
                        $updateBalance = [];
                        if ($modePlay === 0) {
                            $updateUser['balance'] = DB::raw("balance+$amount");
                        } else {
                            $updateUser['bonus_balance'] = DB::raw("bonus_balance+$amount");
                        }

                        User::where('id', $params['user']->id)
                            ->update($updateBalance);

                        $userAfterUpdate = User::select($userFields)->where('id', $params['user']->id)->first();
                        $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->full_balance);

                        if ($userAfterUpdate->bonus_balance < 0
                            or $userAfterUpdate->balance < 0
                            or $balanceAfterTransaction < 0) {
                            throw new \Exception('Insufficient funds', 403);
                        }

                        $pantalloTransaction = [
                            'system_id' => $externalTransactionId,
                            'transaction_id' => $transaction->id,
                            'action_id' => $typesActions[$caseAction],
                            'amount' => $amount,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $balanceAfterTransaction,
                            //check null for ald transaction
                            'game_id' => !is_null($transactionHas->game_id)
                                ? $transactionHas->game_id : null
                        ];
                        GamesPantalloTransaction::create($pantalloTransaction);
                        $balance = $balanceAfterTransaction;
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
                $response['balance'] = isset($balanceBefore) ? $balanceBefore : null;
            }
        }
        DB::commit();

        //finish debug
        $debugGameResult = $debugGame->end();

        RawLog::create([
            'type_id' => 2,
            'request' => GeneralHelper::fullRequest(),
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult)
        ]);

        return $response;
    }


    public function balance($params)
    {
        //app accuracyValues
        $response = [
            'status' => 200,
            'balance' => bcadd($params['user']->balance, $params['user']->balance, 5)
        ];
        return $response;
    }

    public function debit($params)
    {
        $response = [];
        return $response;
    }

    public function credit($params)
    {
        $response = [];
        return $response;
    }

    public function rollback($params)
    {
        $response = [];
        return $response;
    }

    /**
     * @param $request
     * @return array|mixed
     */
    public function freeRound($request)
    {
        //input
        $available = $request->available;
        $timeFreeRound = $request->timeFreeRound;
        $gamesIds = $request->gamesIds;

        $debugGame = new DebugGame();
        $debugGame->start();

        DB::beginTransaction();
        try {
            $user = $request->user();
            $pantalloGames = new PantalloGames;
            $playerExists = $pantalloGames->playerExists([
                'user_username' => $user->id,
            ], true);

            //active player request
            if ($playerExists->response === false) {
                throw new \Exception('User is not found');
            }
            $player = $playerExists->response;

            $validTo = new \DateTime();
            $validTo->modify("+$timeFreeRound second");

            $freeRounds = $pantalloGames->addFreeRounds([
                'playerids' => $player->id,
                'gameids' => $gamesIds,
                'available' => $available,
                'validTo' => $validTo->format('Y-m-d')
            ], true);

            $freeRoundsResponse = json_decode($freeRounds->response);

            $freeRoundsId = $freeRoundsResponse->freeround_id;
            $freeRoundCreated = $freeRoundsResponse->created;

            GamesPantalloFreeRounds::create([
                'user_id' => $user->id,
                'round' => $available,
                'valid_to' => $validTo,
                'created' => $freeRoundCreated,
                'free_round_id' => $freeRoundsId
            ]);

            $response = [
                'success' => true,
                'freeRoundId' => $freeRoundsId
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            //rollback free rounds
            if (isset($freeRoundsId)) {
                $pantalloGames->removeFreeRounds([
                    'playerids' => $player->id,
                    'freeround_id' => $freeRoundsId
                ], true);
            }

            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();

            $response = [
                'success' => false,
                'message' => $errorMessage . ' Line:' . $errorLine
            ];
        }
        DB::commit();

        $debugGameResult = $debugGame->end();
        RawLog::create([
            'type_id' => 4,
            'request' => GeneralHelper::fullRequest(),
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult)
        ]);

        return $response;
    }
}