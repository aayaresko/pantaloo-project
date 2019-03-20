<?php

namespace App\Bonuses;

use DB;
use App\User;
use App\BonusLog;
use Carbon\Carbon;
use App\UserBonus;
use App\Transaction;
use App\Models\GamesList;
use App\Bonus as BonusModel;
use \Illuminate\Http\Request;
use App\Modules\Games\PantalloGamesSystem;
use App\Models\Pantallo\GamesPantalloSessionGame;

class FreeSpins extends \App\Bonuses\Bonus
{
    public static $id = 1;
    public static $maxAmount = 60;
    protected $playFactor = 33;
    protected $expireDays = 1;
    protected $freeSpins = 50;
    protected $timeActiveBonusDays = 5;

    /**
     * @return bool
     */
    public function bonusAvailable()
    {
        $user = $this->user;
        $createdUser = $user->created_at;
//        $timeActiveBonusSec = strtotime("$this->timeActiveBonusDays day", 0);
//
//        $allowedDate = $createdUser->modify("+$timeActiveBonusSec second");
//        $currentDate = new Carbon();
//
//        if ($allowedDate < $currentDate) {
//            return false;
//        }

        $countBonuses = $this->user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count();
        if ($countBonuses > 0) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function activate()
    {
        $response = [
            'success' => true,
            'message' => 'Done'
        ];
        $user = $this->user;
        $configBonus = config('bonus');
        $slotTypeId = config('appAdditional.slotTypeId');

        DB::beginTransaction();
        try {
            $createdUser = $user->created_at;
            $allowedDate = $createdUser->modify("+$this->timeActiveBonusDays days");
            $currentDate = new Carbon();

            if ($this->active_bonus) {
                throw new \Exception('You already use bonus.');
            }

            if ($this->user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count() > 0) {
                throw new \Exception('You can\'t use this bonus. Can only be used once.');
            }

            if ((int)$user->email_confirmed === 0) {
                throw new \Exception('Your email is not confirm.');
            }

            if ($allowedDate < $currentDate) {
                throw new \Exception('You can\'t use this bonus. Read terms.');
            }

            $date = Carbon::now();
            $date->modify('+' . $this->expireDays . 'days');

            $bonus = BonusModel::where('id', static::$id)->firstOrFail();

            $bonusUser = UserBonus::create([
                'expires_at' => $date,
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
            ]);

            //get all games for free
            $request = new Request;

            //add user for request - for lib
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            //get games for free spins
            $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
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

            $gamesIds = implode(',', array_map(function ($item) {
                return $item->system_id;
            }, $freeRoundGames));

            $request->merge(['gamesIds' => $gamesIds]);
            $request->merge(['available' => $this->freeSpins]);
            $request->merge(['timeFreeRound' => strtotime("$this->expireDays day", 0)]);

            $pantalloGamesSystem = new PantalloGamesSystem();
            $freeRound = $pantalloGamesSystem->freeRound($request);

            if ($freeRound['success'] === false) {
                throw new \Exception('Problem with provider free spins');
            }

            BonusLog::updateOrCreate(
                [
                    'bonus_id' => $bonusUser->id,
                    'operation_id' => $configBonus['operation']['active']
                ],
                ['status' => json_encode($response)]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            //dd($errorMessage);
            throw new \Exception($errorMessage);
        }
        DB::commit();

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function realActivation()
    {
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;
        $error = 0;
        $user = $this->user;
        $errorMessage = 0;
        $response = [
            'success' => true,
            'message' => 'Done'
        ];
        DB::beginTransaction();
        try {
            //to define start transaction wagered
            $dateStartBonus = $activeBonus->created_at;
            $transaction = $this->user->transactions()->where([
                ['created_at', '>', $dateStartBonus],
                ['type', '=', 10],
            ])->orderBy('id', 'DESC')->first();

            //no transactions for increasing amount bonus
            if (is_null($transaction)) {
                throw new \Exception('No transactions yet with type 10');
            }

            $now = Carbon::now();

            //180 time when we realy get all transaction
            if ($now->format('U') - $transaction->created_at->format('U') > 60) {

                //get transaction to real for for this bonus
                $transactionToReal = Transaction::where([
                    ['created_at', '>', $dateStartBonus],
                    ['type', '=', 7],
                    ['user_id', '=', $user->id]
                ])->first();

                //to do check to sum
                if (!is_null($transactionToReal)) {
                    //get only transaction after
                    $freeSpinWin = $this->user->transactions()->where([
                        ['id', '>', $transactionToReal->id],
                        ['type', '=', 10]
                    ])->sum('bonus_sum');
                } else {
                    $freeSpinWin = $this->user->transactions()->where([
                        ['created_at', '>', $dateStartBonus],
                        ['type', '=', 10]
                    ])->sum('bonus_sum');
                    if (is_null($freeSpinWin)) {
                        $freeSpinWin = 0;
                    }
                }

                UserBonus::where('id', $activeBonus->id)->update([
                    'activated' => 1,
                    'data' => json_encode([
                        'free_spin_win' => $freeSpinWin,
                        'wagered_sum' => $freeSpinWin * $this->playFactor,
                        'transaction_id' => $transaction->id,
                        'dateStart' => $dateStartBonus
                    ])
                ]);

            } else {
                throw new \Exception('All transactions not yet received.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error = 1;
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];
        }
        DB::commit();

        BonusLog::updateOrCreate(
            [
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['realActivation']
            ],
            ['status' => json_encode($response)]
        );

        if ($error === 1) {
            throw new \Exception($errorMessage);
        }

        return true;
    }

    public function close()
    {
        $error = 0;
        $errorMessage = 0;
        $user = $this->user;
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        DB::beginTransaction();
        try {
            if ($this->hasBonusTransactions()) {
                throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
            }

            $response = [
                'success' => true,
                'message' => 'The condition is not satisfied'
            ];

            if ($this->active_bonus->activated == 1) {
                $wageredSum = $this->get('wagered_sum');
                if ($wageredSum > 0 and $this->getPlayedSum() >= $wageredSum) {

                    $response = [
                        'success' => true,
                        'message' => 'Bonus to real transfer'
                    ];

                    $transaction = new Transaction();
                    $winAmount = $user->bonus_balance;

                    $bonusAmount = -1 * $user->bonus_balance;
                    $transaction->bonus_sum = $bonusAmount;
                    $transaction->sum = $winAmount;
                    $transaction->comment = 'Bonus to real transfer';
                    $transaction->type = 7;
                    $transaction->user()->associate($this->user);
                    $transaction->save();

                    User::where('id', $user->id)->update([
                        'balance' => DB::raw("balance+$winAmount"),
                        'bonus_balance' => 0
                    ]);

                    $now = Carbon::now();

                    if ($this->active_bonus->expires_at->format('U') < $now->format('U')) {
                        $activeBonus->delete();
                        $response = [
                            'success' => true,
                            'message' => 'Done. Close'
                        ];
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = 1;
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];

        }

        BonusLog::updateOrCreate(
            [
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['close']
            ],
            ['status' => json_encode($response)]
        );

        if ($error === 1) {
            throw new \Exception($errorMessage);
        }

        return true;
    }

    /**
     * @param bool $reason
     * @return bool
     * @throws \Exception
     */
    public function cancel($reason = false)
    {
        $user = $this->user;
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;
        $slotTypeId = config('appAdditional.slotTypeId');

        DB::beginTransaction();
        try {
            //check to enters to games
            if ($this->hasBonusTransactions()) {
                throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
            }

            $dateStartBonus = $activeBonus->created_at;
            //and add only slots games for this to do
            //get only slots games
            $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
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

            $freeRoundGames = array_map(function ($item) {
                return $item->id;
            }, $freeRoundGames);

            $openGames = GamesPantalloSessionGame::join('games_pantallo_session',
                'games_pantallo_session.system_id', '=', 'games_pantallo_session_game.session_id')
                ->whereIn('game_id', $freeRoundGames)
                ->where([
                    ['games_pantallo_session_game.created_at', '>', $dateStartBonus],
                    ['games_pantallo_session.user_id', '=', $user->id],
                ])->first();

            if (!is_null($openGames)) {
                throw new \Exception('Free rounds are already active. We cannot deactivate them.');
            }

            //to do all sum last transaction if multi bonuses
            $bonusAmount = -1 * $user->bonus_balance;
            $transaction = new Transaction();
            $transaction->bonus_sum = $bonusAmount;
            $transaction->sum = 0;
            $transaction->comment = $reason;
            $transaction->type = 6;
            $transaction->user()->associate($user);
            $transaction->save();

            User::where('id', $user->id)->update([
                'bonus_balance' => DB::raw("bonus_balance+$bonusAmount")
            ]);

            $updateUser = User::where('id', $user->id)->first();

            if ((float)$updateUser->bonus_balance === (float)0) {
                $activeBonus->delete();
            }

            $request = new Request;
            //add user for request - for lib
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            if ((int)$activeBonus->activated === 0) {
                $pantalloGamesSystem = new PantalloGamesSystem();
                $freeRound = $pantalloGamesSystem->removeFreeRounds($request);
                if ($freeRound['success'] === false) {
                    throw new \Exception('Problem with provider free spins. Operation: removeFreeRounds');
                }
            }

            $response = [
                'success' => true,
                'message' => 'Done'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();

            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];

            BonusLog::updateOrCreate(
                [
                    'bonus_id' => $activeBonus->id,
                    'operation_id' => $configBonus['operation']['cancel']
                ],
                ['status' => json_encode($response)]
            );

            throw new \Exception($errorMessage);
        }

        DB::commit();

        BonusLog::updateOrCreate(
            [
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['cancel']
            ],
            ['status' => json_encode($response)]
        );

        return true;
    }

    /**
     *
     * check if someone is playing now
     *
     * @param int $minutes
     * @return bool
     */
    public function hasBonusTransactions($minutes = 1)
    {
        //only bonus transaction
        $date = Carbon::now();
        $date->modify('-' . $minutes . ' minutes');

        $transaction = $this->user->transactions()->where('created_at', '>', $date)->first();

        if (!$transaction) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return float|int
     */
    public function getPlayedSum()
    {
        if ($this->active_bonus->activated == 1) {
            $playedSum = -1 * $this->user->transactions()
                    ->where('id', '>', $this->get('transaction_id'))
                    ->where('type', 1)
                    ->sum('bonus_sum');
            return $playedSum;
        }
        return 0;
    }

    /**
     * @return float|int
     */
    public function getPercent()
    {
        if ($this->active_bonus->activated == 1) {
            $played_sum = $this->getPlayedSum();
            $percent = floor($played_sum / $this->get('wagered_sum') * 100);
            return $percent;
        }
        return 0;
    }

    public function getStatus()
    {

    }
}