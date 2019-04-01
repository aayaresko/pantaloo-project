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

            GamesPantalloSessionGame::create([
                'session_id' => $idLogin,
                'gamesession_id' => $getGame->gamesession_id,
                'game_id' => $game->id
            ]);

            DB::commit();

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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

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
        //start log

        $rawLog = RawLog::create([
            'type_id' => 2,
            'request' => GeneralHelper::fullRequest(),
        ]);

        DB::beginTransaction();
        try {
            /*preparation parameters*/
            //validation
            $modePlay = 0; //play real money
            $notActiveBonus = 0;
            $bonusLimit = 0;
            $params = [];
            $slotTypeId = config('appAdditional.slotTypeId');
            $requestParams = $request->query();
            Log::info($requestParams);

            $configPantalloGames = config('pantalloGames');
            $salt = $configPantalloGames['additional']['salt'];
            $typesActions = $configPantalloGames['additional']['action'];
            $accuracyValues = GeneralHelper::getAccuracyValues();

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
            ];

            //add additional fields
            $additionalFieldsUser = [
                'affiliates.id as partner_id',
                'affiliates.commission as partner_commission',
                'user_bonuses.id as bonus',
                'user_bonuses.bonus_id as bonus_id',
                'user_bonuses.created_at as start_bonus',
                'bonus_n_active.id as bonus_n_active',
                'bonus_n_active.bonus_id as bonus_n_active_id',
                'bonus_n_active.created_at as start_bonus_n_active',
                'bonus_n_active.expires_at as expires_at',
            ];

            $params['user'] = User::select(array_merge($userFields, $additionalFieldsUser))
                ->leftJoin('users as affiliates', 'users.agent_id', '=', 'affiliates.id')
                ->leftJoin('user_bonuses', function ($join) {
                    $join->on('users.id', '=', 'user_bonuses.user_id')
                        ->where('user_bonuses.activated', '=', 1)
                        ->whereNull('user_bonuses.deleted_at');
                })
                ->leftJoin('user_bonuses as bonus_n_active', function ($join) {
                    $join->on('users.id', '=', 'bonus_n_active.user_id')
                        ->whereNull('bonus_n_active.deleted_at');
                })
                ->where([
                    ['users.id', '=', $params['session']->user_id],
                ])->first();

            if (is_null($params['session'])) {
                throw new \Exception('User is not found');
            }

            $params['game'] = GamesList::select(['id', 'system_id'])
                ->where('system_id', $requestParams['game_id'])->first();
            if (is_null($params['game'])) {
                throw new \Exception('Game is not found');
            }

            $balanceBefore = GeneralHelper::formatAmount($params['user']->balance);

            if (!is_null($params['user']->bonus_n_active)) {
                $typeBonus = $params['user']->bonus_n_active_id;
                $bonusClass = config('bonus.classes')[$typeBonus];
                $bonusLimit = $bonusClass::$maxAmount;
                $notActiveBonus = 1;
                //get bonus and set limit bonus
            }

            if (!is_null($params['user']->bonus)) {
                $modePlay = 1;
                //get bonus and set limit bonus
            }

            //get type games
            //mode if isset ids games
            $gameIdRequest = isset($requestParams['game_id']) ? $requestParams['game_id'] : null;

            if (!is_null($gameIdRequest)) {
                $slotsGame = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
                    ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                    ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                    ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                    ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                    ->whereIn('games_types_games.type_id', [$slotTypeId])
                    ->where([
                        ['games_list.system_id', '=', $gameIdRequest],
                        ['games_types_games.extra', '=', 1],
                        ['games_list.active', '=', 1],
                        ['games_types.active', '=', 1],
                        ['games_categories.active', '=', 1],
                    ])->groupBy('games_types_games.game_id')->first();

                $typeOpenGame = $slotsGame;
            } else {
                $slotsGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
                    ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                    ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                    ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                    ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                    ->whereIn('games_types_games.type_id', [$slotTypeId])
                    ->where([
                        ['games_types_games.extra', '=', 1],
                        ['games_list.active', '=', 1],
                        ['games_types.active', '=', 1],
                        ['games_categories.active', '=', 1],
                    ])
                    ->groupBy('games_types_games.game_id')->get();

                $slotsGameIds = array_map(function ($item) {
                    return $item->id;
                }, $slotsGames);

                $typeOpenGame = GamesPantalloSessionGame::join('games_pantallo_session',
                    'games_pantallo_session.system_id', '=', 'games_pantallo_session_game.session_id')
                    ->whereIn('game_id', $slotsGameIds)
                    ->where([
                        ['games_pantallo_session.user_id', '=', $params['user']->id],
                    ])
                    ->selelct([
                        'games_pantallo_session_game.id',
                    ])
                    ->orderBy('id', 'desc')
                    ->first();
            }

            if (!is_null($typeOpenGame)) {
                $balanceBefore = GeneralHelper::formatAmount($params['user']->full_balance);
            }
            //finish get games

            if ($balanceBefore < 0) {
                throw new \Exception('Insufficient funds', 403);
            }
            $action = $requestParams['action'];
            if ($action !== 'balance') {
                $gamesSessionIdThem = $requestParams['gamesession_id'];
                $gamesSession = GamesPantalloSessionGame::where([
                    'session_id' => $params['session']->system_id,
                    'gamesession_id' => $gamesSessionIdThem,
                ])->first();
                if (is_null($gamesSession)) {
                    throw new \Exception('Games session is not found.' .
                        ' This user is not playing currently.', 500);
                }
                $gamesSessionId = $gamesSession->id;
            }

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
                            $createParams['bonus_sum'] = 0;
                        } else {
                            if ((float)$params['user']->balance < abs($amount)) {
                                $createParams['sum'] = -1 * $params['user']->balance;
                                $createParams['bonus_sum'] = -1 * GeneralHelper::formatAmount(
                                        abs($amount) - abs($createParams['sum']));

                            } elseif ((float)$params['user']->balance < 0) {
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = $amount;
                            } else {
                                $createParams['sum'] = $amount;
                                $createParams['bonus_sum'] = 0;
                            }
                        }

                        //if free spins transactions
                        if (isset($requestParams['is_freeround_bet']) and $requestParams['is_freeround_bet'] == 1) {
                            if ($notActiveBonus === 1) {
                                $createParams['type'] = 9;
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = 0;
                            } else {
                                $createParams['type'] = 9;
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = 0;
                            }
                        }

                        $transaction = Transaction::create($createParams);

                        //edit balance user
                        $updateUser = [];
                        $updateUser['balance'] = DB::raw("balance+{$createParams['sum']}");
                        $updateUser['bonus_balance'] = DB::raw("bonus_balance+{$createParams['bonus_sum']}");

                        User::where('id', $params['user']->id)
                            ->update($updateUser);

                        $userAfterUpdate = User::select($userFields)->where('id', $params['user']->id)->first();

                        if (!is_null($typeOpenGame)) {
                            $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->full_balance);
                        } else {
                            $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->balance);
                        }

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
                            'game_id' => $params['game']->id,
                            'games_session_id' => $gamesSessionId,
                            'real_action_id' => 1,
                        ];

                        GamesPantalloTransaction::create($pantalloTransaction);
                        $balance = $balanceAfterTransaction;
                    } else {
                        $balance = $transaction->balance_after;
                    }
                    $response = [
                        'status' => 200,
                        'balance' => (float)$balance,
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

                    if ($amount < 0) {
                        throw new \Exception('For this operation. Amount should will be bigger zero');
                    }

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
                            $createParams['bonus_sum'] = 0;
                        } else {
                            //find last credit transaction and make count
                            $lastTransaction = Transaction::leftJoin('games_pantallo_transactions',
                                'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
                                ->where([
                                    ['games_pantallo_transactions.action_id', '=', 1],
                                    ['games_pantallo_transactions.games_session_id', '=', $gamesSessionId]
                                ])->where(function ($query) {
                                    $query->where('transactions.sum', '<>', 0)
                                        ->orWhere('transactions.bonus_sum', '<>', 0);
                                })
                                ->select([
                                    'transactions.id',
                                    'action_id',
                                    'transactions.sum',
                                    'transactions.bonus_sum',
                                    'games_pantallo_transactions.amount as amount',
                                    'games_pantallo_transactions.game_id as game_id',
                                    'games_pantallo_transactions.balance_after as balance_after'
                                ])->orderBy('id', 'DESC')->first();

                            //to do throw exception

                            //if is null - this after bonus money
                            if (!is_null($lastTransaction)) {
                                if ((float)$lastTransaction->bonus_sum !== 0 and (float)$lastTransaction->sum !== 0) {
                                    $totalSum = abs($lastTransaction->sum + $lastTransaction->bonus_sum);

                                    $percentageSum = abs($lastTransaction->sum) / $totalSum;
                                    $createParams['sum'] = GeneralHelper::formatAmount($amount * $percentageSum);

                                    $percentageBonusSum = abs($lastTransaction->bonus_sum) / $totalSum;
                                    $createParams['bonus_sum'] = GeneralHelper::formatAmount($amount * $percentageBonusSum);
                                } elseif ((float)$params['user']->balance < 0) {
                                    $createParams['sum'] = 0;
                                    $createParams['bonus_sum'] = $amount;
                                } else {
                                    $createParams['sum'] = $amount;
                                    $createParams['bonus_sum'] = 0;
                                }
                            } else {
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = $amount;
                            }
                        }

                        if (isset($requestParams['is_freeround_win']) and $requestParams['is_freeround_win'] == 1) {
                            //sum all previous transaction and count and make yes or no
                            //this sum for win only free spins
                            //this mean for bonus active
                            if ($notActiveBonus === 1) {
                                $amountFreeSpins = $amount;

                                $startDateBonus = $params['user']->start_bonus_n_active;

                                $transactionSumBonus = Transaction::where([
                                    ['created_at', '>', $startDateBonus],
                                    ['type', '=', 10],
                                    ['user_id', '=', $params['user']->id]
                                ])->sum('bonus_sum');

                                if ($bonusLimit !== 0) {
                                    $allowedBonusFunds = $bonusLimit - $transactionSumBonus;
                                    if ($allowedBonusFunds <= $amount) {
                                        $amountFreeSpins = $allowedBonusFunds;
                                        if ($amountFreeSpins < 0) {
                                            $amountFreeSpins = 0;
                                        }
                                    }
                                }

                                $createParams['type'] = 10;
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = $amountFreeSpins;
                            } else {
                                $createParams['type'] = 10;
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = 0;
                            }
                        }

                        $transaction = Transaction::create($createParams);

                        //edit balance user
                        $updateUser = [];
                        $updateUser['balance'] = DB::raw("balance+{$createParams['sum']}");
                        $updateUser['bonus_balance'] = DB::raw("bonus_balance+{$createParams['bonus_sum']}");

                        User::where('id', $params['user']->id)
                            ->update($updateUser);

                        $userAfterUpdate = User::select($userFields)->where('id', $params['user']->id)->first();

                        if (!is_null($typeOpenGame)) {
                            $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->full_balance);
                        } else {
                            $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->balance);
                        }

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
                            'game_id' => $params['game']->id,
                            'games_session_id' => $gamesSessionId,
                            'real_action_id' => 2,
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
                            'transactions.sum',
                            'transactions.bonus_sum',
                            'action_id',
                            DB::raw('(transactions.sum + transactions.bonus_sum) as real_amount'),
                            'games_pantallo_transactions.amount as amount',
                            'games_pantallo_transactions.game_id as game_id',
                            'games_pantallo_transactions.balance_after as balance_after'
                        ])->first();

                    if (is_null($transactionHas)) {
                        throw new \Exception('Does not have a transaction', 404);
                    }

                    if ((int)$transactionHas->action_id === 2 or (int)$transactionHas->action_id === 10) {
                        //CHECK THIS = ASK MAX
                        //without abs because only action 2
                        if ((float)$transactionHas->real_amount > $balanceBefore) {
                            throw new \Exception('Insufficient funds', 403);
                        }
                    }
                    $amount = -1 * GeneralHelper::formatAmount($transactionHas->real_amount);

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
                            $createParams['bonus_sum'] = 0;
                        } else {
                            if ((float)$transactionHas->bonus_sum !== 0 and (float)$transactionHas->sum !== 0) {
                                $createParams['sum'] = (-1) * $transactionHas->sum;
                                $createParams['bonus_sum'] = (-1) * $transactionHas->bonus_sum;
                            } elseif ((float)$params['user']->balance < 0) {
                                $createParams['sum'] = 0;
                                $createParams['bonus_sum'] = $amount;
                            } else {
                                $createParams['sum'] = $amount;
                                $createParams['bonus_sum'] = 0;
                            }
                        }

                        if ($transactionHas->action_id == 9) {
                            $createParams['type'] = 10;
                            $createParams['sum'] = 0;
                            $createParams['bonus_sum'] = 0;
                        }

                        //if bonus not active already - we can to take away money
                        if ($transactionHas->action_id == 10) {
                            $createParams['type'] = 9;
                            $createParams['sum'] = 0;
                            $createParams['bonus_sum'] = $amount;
                        }

                        $transaction = Transaction::create($createParams);

                        //edit balance user
                        $updateUser = [];
                        $updateUser['balance'] = DB::raw("balance+{$createParams['sum']}");
                        $updateUser['bonus_balance'] = DB::raw("bonus_balance+{$createParams['bonus_sum']}");

                        User::where('id', $params['user']->id)
                            ->update($updateUser);

                        $userAfterUpdate = User::select($userFields)->where('id', $params['user']->id)->first();

                        if (!is_null($typeOpenGame)) {
                            $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->full_balance);
                        } else {
                            $balanceAfterTransaction = GeneralHelper::formatAmount($userAfterUpdate->balance);
                        }

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
                                ? $transactionHas->game_id : null,
                            'games_session_id' => $gamesSessionId,
                            'real_action_id' => 3,
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
            DB::commit();
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

        //finish debug
        $debugGameResult = $debugGame->end();

        RawLog::where('id', $rawLog->id)->update([
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
            $userId = $user->id;
            $pantalloGames = new PantalloGames;
            $playerExists = $pantalloGames->playerExists([
                'user_username' => $user->id,
            ], true);

            if ($playerExists->response === false) {
                $playerResponse = $pantalloGames->createPlayer([
                    'user_id' => $userId,
                    'user_username' => $userId,
                    'password' => self::PASSWORD
                ], true);
            } else {
                $playerResponse = $playerExists;
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

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            //rollback free rounds
            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();

            $response = [
                'success' => false,
                'message' => $errorMessage . ' Line:' . $errorLine
            ];

            if (isset($freeRoundsId)) {
                $removeFreeRounds = $pantalloGames->removeFreeRounds([
                    'playerids' => $player->id,
                    'freeround_id' => $freeRoundsId
                ], true);
                $response['removeFreeRounds'] = $removeFreeRounds;
                $response['freeRoundsResponse'] = $freeRoundsResponse;
            }
        }

        $debugGameResult = $debugGame->end();

        //logs to any connection to db
        $date = new \DateTime();
        DB::connection('logs')->table('raw_log')->insert([
            'type_id' => 4,
            'request' => GeneralHelper::fullRequest(),
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult),
            'created_at' => $date,
            'updated_at' => $date
        ]);

        return $response;
    }


    public function removeFreeRounds($request)
    {
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

            $getFreeRounds = GamesPantalloFreeRounds::where([
                'user_id' => $user->id
            ])->orderBy('id', 'DESC')->first();

            if (is_null($getFreeRounds)) {
                throw new \Exception('Free Rounds did not found');
            }

            $removeFreeRounds = $pantalloGames->removeFreeRounds([
                'playerids' => $player->id,
                'freeround_id' => $getFreeRounds->free_round_id
            ], true);

            if ((int)$removeFreeRounds->error > 0) {
                throw new \Exception('removeFreeRounds method was worked');
            }

            if (empty($removeFreeRounds->response->successfull_removals)) {
                throw new \Exception('Free rounds was not removed. Provider error');
            }

            $response = [
                'success' => true,
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();

            if (isset($removeFreeRounds)) {
                $response['removeFreeRounds'] = $removeFreeRounds;
            }

            $response = [
                'success' => false,
                'message' => $errorMessage . ' Line:' . $errorLine
            ];
        }

        $debugGameResult = $debugGame->end();

        //logs to any connection to db
        $date = new \DateTime();
        DB::connection('logs')->table('raw_log')->insert([
            'type_id' => 5,
            'request' => GeneralHelper::fullRequest(),
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult),
            'created_at' => $date,
            'updated_at' => $date
        ]);

        return $response;
    }
}