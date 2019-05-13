<?php

namespace App\Bonuses;

use DB;
use Log;
use App\User;
use App\BonusLog;
use Carbon\Carbon;
use App\UserBonus;
use App\Transaction;
use GuzzleHttp\Client;
use App\Models\GamesList;
use Helpers\GeneralHelper;
use App\Bonus as BonusModel;
use \Illuminate\Http\Request;
use App\Models\LastActionGame;
use App\Events\OpenBonusEvent;
use App\Events\WagerDoneEvent;
use App\Events\CloseBonusEvent;
use App\Models\SystemNotification;
use App\Modules\Games\PantalloGamesSystem;
use App\Models\Pantallo\GamesPantalloSessionGame;

class FreeSpins extends \App\Bonuses\Bonus
{
    public static $id = 1;
    public static $maxAmount = 20;

    protected $playFactor = 50;
    protected $expireDays = 10;
    protected $freeSpins = 50;
    protected $timeActiveBonusDays = 5;
    protected $minDeposit = 5;

    const SPECIAL = 1313;

    public function bonusAvailable()
    {
        $user = $this->user;
        $createdUser = $user->created_at;

        //hide if user 
        $timeActiveBonusSec = strtotime("$this->timeActiveBonusDays day", 0);

        $allowedDate = $createdUser->modify("+$timeActiveBonusSec second");
        $currentDate = new Carbon();

        if ($allowedDate < $currentDate) {
            return false;
        }
        //hide if user 

        $countBonuses = $this->user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count();

        if ($countBonuses > 0) {
            return false;
        }

        return true;
    }


    public function activate()
    {
        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $slotTypeId = config('appAdditional.slotTypeId');
        $banedBonusesCountries = config('appAdditional.banedBonusesCountries');

        try {
            $createdUser = $user->created_at;
            $allowedDate = $createdUser->modify("+$this->timeActiveBonusDays days");
            $currentDate = new Carbon();

            //temporary
            //throw new \Exception('Bonus is temporarily unavailable');
            //temporary

            $codeCountryCurrent = GeneralHelper::visitorCountryCloudFlare();

            //baned country
            if (in_array($codeCountryCurrent, $banedBonusesCountries)) {
                throw new \Exception('You cannot activate this bonus in' .
                    ' accordance with clause 1.19 of the bonus terms & conditions.');
            }

            //baned country
            if (!is_null($user->country)) {
                if (in_array($user->country, $banedBonusesCountries)) {
                    throw new \Exception('You cannot activate this bonus in' .
                        ' accordance with clause 1.19 of the bonus terms & conditions.');
                }
            }

            if ($this->active_bonus) {
                if ($this->active_bonus->bonus_id != static::$id) {
                    throw new \Exception('You cannot activate this bonus ' .
                        'as there is already an active bonus.');
                } else {
                    throw new \Exception('This bonus is already active.');
                }
            }

            if ($this->user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count() > 0) {
                throw new \Exception('This bonus is already used.');
            }

            if ((int)$user->email_confirmed === 0) {
                throw new \Exception('To activate this bonus, you need to confirm your email address first.
                 Did not receive the activation mail? You can resend it in the Settings section of your profile.');
            }

            if ($allowedDate < $currentDate) {
                throw new \Exception('You cannot activate this bonus in accordance ' .
                    'with clause 2.2 of the bonus terms & conditions.');
            }

            //IpQuality
            $ipCurrent = GeneralHelper::visitorIpCloudFlare();
            $ipQualityScoreUrl = config('appAdditional.ipQualityScoreUrl');
            $ipQualityScoreKey = config('appAdditional.ipQualityScoreKey');

            //5 to do config
            $client = new Client(['timeout' => 5]);
            $responseIpQuality = $client->request('GET', $ipQualityScoreUrl . '/' . $ipQualityScoreKey . '/' . $ipCurrent);
            $responseIpQualityJson = json_decode($responseIpQuality->getBody()->getContents(), true);

            //89.39.107.197
            //temporary for test
            if ($ipCurrent != '89.39.107.197') {
                if (isset($responseIpQualityJson['success'])) {
                    if ($responseIpQualityJson['success'] == true) {
                        if ($responseIpQualityJson['vpn'] == true or $responseIpQualityJson['tor'] == true) {
                            throw new \Exception('Free spins are not available while using VPN/Proxy');
                        }
                    }
                }
            }
            //IpQuality

            $date = Carbon::now();
            $date->modify('+' . $this->expireDays . 'days');

            $bonusUser = UserBonus::create([
                'user_id' => $user->id,
                'bonus_id' => static::$id,
                'data' => [
                    'free_spin_win' => 0,
                    'wagered_sum' => 0,
                    'transaction_id' => 0,
                    'total_deposit' => 0,
                    'wagered_deposit' => 0,
                    'wagered_amount' => 0,
                    'wagered_bonus_amount' => 0,
                    'dateStart' => $currentDate,
                ],
                'activated' => 0,
                'expires_at' => $date,
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
                    ['games_list.active', '=', 1],
                    ['games_list.free_round', '=', 1],
                    ['games_types_games.extra', '=', 1],
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
            $request->merge(['mode' => 0]);//only once

            $pantalloGamesSystem = new PantalloGamesSystem();
            $freeRound = $pantalloGamesSystem->freeRound($request);

            if ($freeRound['success'] === false) {
                throw new \Exception('Problem with provider free spins');
            }

            User::where('id', $user->id)->update([
                'bonus_id' => static::$id
            ]);

            event(new OpenBonusEvent($user, 'welcome bonus'));

            $response = [
                'success' => true,
                'message' => 'Done'
            ];

            BonusLog::updateOrCreate(
                [
                    'bonus_id' => $bonusUser->id,
                    'operation_id' => $configBonus['operation']['active']
                ],
                ['status' => json_encode($response)]
            );

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => $errorMessage
                //'message' => 'Message:' . $errorMessage. ' Line:' . $errorLine
            ];
        }

//         DB::connection('logs')->table('bonus_logs')->insertGetId([
//            'bonus_id' => $bonusUser->id,
//            'operation_id' => $configBonus['operation']['active'],
//            'status' => json_encode($response),
//            'created_at' => $date,
//            'updated_at' => $date
//        ]);

        return $response;
    }


    public function realActivation($params)
    {
        $amount = $params['amount'];
        $transactionId = $params['transactionId'];

        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $rawLog = DB::connection('logs')->table('bonus_logs')
            ->where('bonus_id', '=', $activeBonus->id)
            ->where('operation_id', '=', $configBonus['operation']['realActivation'])
            ->first();

        if ($rawLog) {
            $rawLogId = $rawLog->id;
        } else {
            $rawLogId = DB::connection('logs')->table('bonus_logs')->insertGetId([
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['realActivation'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }

        try {
            $freeSpinWinOld = $this->dataBonus['free_spin_win'];
            $freeSpinWin = GeneralHelper::formatAmount($amount + (float)$freeSpinWinOld);
            $this->dataBonus['free_spin_win'] = $freeSpinWin;
            $this->dataBonus['wagered_sum'] = $freeSpinWin * $this->playFactor;


            $dataUpdateBonus = [];
            if ((int)$activeBonus->activated == 0) {
                //transaction_id
                $this->dataBonus['transaction_id'] = $transactionId;
                $dataUpdateBonus['activated'] = 1;
            }

            $dataUpdateBonus['data'] = json_encode($this->dataBonus);

            UserBonus::where('id', $activeBonus->id)->update($dataUpdateBonus);

            $response = [
                'success' => true,
                'message' => 'Done'
            ];

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];
        }

        DB::connection('logs')->table('bonus_logs')->where('id', $rawLogId)->update([
            'status' => json_encode($response)
        ]);

        return $response;
    }

    public function close($mode = 0)
    {
        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;
        $bonusLimit = self::$maxAmount;

        $rawLog = DB::connection('logs')->table('bonus_logs')
            ->where('bonus_id', '=', $activeBonus->id)
            ->where('operation_id', '=', $configBonus['operation']['close'])
            ->first();

        if ($rawLog) {
            $rawLogId = $rawLog->id;
        } else {
            $rawLogId = DB::connection('logs')->table('bonus_logs')->insertGetId([
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['close'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }

        try {
            $now = Carbon::now();
            if ($activeBonus->expires_at->format('U') < $now->format('U')) {
                $cancelBonus = $this->cancel('Expired');
                if ($cancelBonus['success'] === false) {
                    throw new \Exception('Method cancel not working');
                } else {
                    throw new \Exception('Expired', self::SPECIAL);
                }
            }

            if ($activeBonus->activated == 0) {
                throw new \Exception('Bonus is not activated');
            }

            //get wageredSum
            $wageredSum = $this->dataBonus['wagered_sum'];
            if ($wageredSum == 0) {
                throw new \Exception('Wagered sum less than zero');
            }

            if ($mode == 1) {
                if ($this->hasBonusTransactions()) {
                    throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
                }
            }

            $totalDeposit = isset($this->dataBonus['total_deposit']) ? (float)$this->dataBonus['total_deposit'] : 0;

            if ($totalDeposit < $this->minDeposit) {
                throw new \Exception('Deposit is not found');
            } else {
                $playedAmount = (float)$this->dataBonus['wagered_amount'];

                if ($playedAmount < $totalDeposit) {
                    throw new \Exception('Deposit not won back');
                }
            }

            //get wager
            $wageredSumCurrent = $this->getPlayedSum();

            if ($wageredSumCurrent >= $wageredSum) {

                $transaction = new Transaction();

                $bonusBalance = (float)$user->bonus_balance;
                //check max amount
                $winAmount = $bonusBalance;
                if ($bonusBalance > $bonusLimit) {
                    $winAmount = $bonusLimit;
                    $trimBonusAmount = GeneralHelper::formatAmount($bonusBalance - $bonusLimit);
                    //create transaction
                    Transaction::create([
                        'sum' => 0,
                        'bonus_sum' => -1 * $trimBonusAmount,
                        'comment' => 'Trim',
                        'type' => 12,
                        'user_id' => $user->id,
                    ]);
                    if ($winAmount <= 0) {
                        $winAmount = 0;
                    }
                }

                $bonusAmount = -1 * $user->bonus_balance;
                $transaction->bonus_sum = $bonusAmount;
                $transaction->sum = $winAmount;
                $transaction->comment = 'Bonus to real transfer';
                $transaction->type = 7;
                $transaction->user()->associate($this->user);
                $transaction->save();

                User::where('id', $user->id)->update([
                    'balance' => DB::raw("balance + $winAmount"),
                    'bonus_balance' => 0,
                    'bonus_id' => null
                ]);

                UserBonus::where('id', $activeBonus->id)->update([
                    'total_amount' => DB::raw("total_amount + $winAmount"),
                ]);

                $activeBonus->delete();

                event(new CloseBonusEvent($user, 'welcome bonus'));
                event(new WagerDoneEvent($user));

                $response = [
                    'success' => true,
                    'message' => 'Bonus to real transfer'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'The condition is not satisfied'
                ];
            }
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];

            if ($errorCode === self::SPECIAL) {
                $response['success'] = true;
            }
        }

        DB::connection('logs')->table('bonus_logs')->where('id', $rawLogId)->update([
            'status' => json_encode($response)
        ]);

        return $response;
    }


    public function cancel($reason = false)
    {
        $date = new \DateTime();
        $user = $this->user;
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;
        $expired = 0;
        $slotTypeId = config('appAdditional.slotTypeId');


        $rawLog = DB::connection('logs')->table('bonus_logs')
            ->where('bonus_id', '=', $activeBonus->id)
            ->where('operation_id', '=', $configBonus['operation']['cancel'])
            ->first();

        if ($rawLog) {
            $rawLogId = $rawLog->id;
        } else {
            $rawLogId = DB::connection('logs')->table('bonus_logs')->insertGetId([
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['cancel'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }

        try {
            $now = Carbon::now();
            if ($activeBonus->expires_at->format('U') < $now->format('U')) {
                $expired = 1;
            }

            if ($expired === 0) {
                $dateStartBonus = $activeBonus->created_at;
                //and add only slots games for this to do
                //get only slots games
//                $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
//                    ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
//                    ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
//                    ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
//                    ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
//                    ->whereIn('games_types_games.type_id', [$slotTypeId])
//                    ->where([
//                        ['games_types_games.extra', '=', 1],
//                        ['games_list.active', '=', 1],
//                        ['games_types.active', '=', 1],
//                        ['games_categories.active', '=', 1],
//                    ])
//                    ->groupBy('games_types_games.game_id')->get();
//
//                $freeRoundGames = array_map(function ($item) {
//                    return $item->id;
//                }, $freeRoundGames);
//
//                $openGames = GamesPantalloSessionGame::join('games_pantallo_session',
//                    'games_pantallo_session.system_id', '=', 'games_pantallo_session_game.session_id')
//                    ->whereIn('game_id', $freeRoundGames)
//                    ->where([
//                        ['games_pantallo_session_game.created_at', '>', $dateStartBonus],
//                        ['games_pantallo_session.user_id', '=', $user->id],
//                    ])->first();
//
//                if (!is_null($openGames)) {
//                    throw new \Exception('Free rounds are already active. We cannot deactivate them.');
//                }

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

//                $request = new Request;
//                //add user for request - for lib
//                $request->merge(['user' => $user]);
//                $request->setUserResolver(function () use ($user) {
//                    return $user;
//                });
//
//                if ((int)$activeBonus->activated === 0) {
//                    $pantalloGamesSystem = new PantalloGamesSystem();
//                    $freeRound = $pantalloGamesSystem->removeFreeRounds($request);
//                    if ($freeRound['success'] === false) {
//                        throw new \Exception('Problem with provider free spins. Operation: removeFreeRounds');
//                    }
//                }

                $response = [
                    'success' => true,
                    'message' => 'Done'
                ];
            } else {
                $bonusAmount = -1 * $user->bonus_balance;
                $transaction = new Transaction();
                $transaction->bonus_sum = $bonusAmount;
                $transaction->sum = 0;
                $transaction->comment = $reason;
                $transaction->type = 6;
                $transaction->user()->associate($user);
                $transaction->save();

                User::where('id', $user->id)->update([
                    'bonus_balance' => DB::raw("bonus_balance+$bonusAmount"),
                    'bonus_id' => null
                ]);

                $updateUser = User::where('id', $user->id)->first();

                if ((float)$updateUser->bonus_balance === (float)0) {
                    $activeBonus->delete();
                }

                $response = [
                    'success' => true,
                    'message' => 'Done.Expire'
                ];
            }
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();

            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];
        }

        DB::connection('logs')->table('bonus_logs')->where('id', $rawLogId)->update([
            'status' => json_encode($response)
        ]);

        return $response;
    }

    public function wagerUpdate($transaction)
    {
        $transactionAmount = abs((float)$transaction['sum']);
        $transactionBonusSum = abs((float)$transaction['bonus_sum']);

        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $rawLog = DB::connection('logs')->table('bonus_logs')
            ->where('bonus_id', '=', $activeBonus->id)
            ->where('operation_id', '=', $configBonus['operation']['wagerUpdate'])//to do config
            ->first();

        if ($rawLog) {
            $rawLogId = $rawLog->id;
        } else {
            $rawLogId = DB::connection('logs')->table('bonus_logs')->insertGetId([
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['wagerUpdate'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }

        try {
            //if was be deposit
            if (isset($this->dataBonus['wagered_deposit']) and (int)$this->dataBonus['wagered_deposit'] === 1) {
                $currentWagerAmount = isset($this->dataBonus['wagered_amount']) ?
                    (float)$this->dataBonus['wagered_amount'] : 0;
                $currentWager = GeneralHelper::formatAmount($currentWagerAmount + $transactionAmount);
            } else {
                $currentWager = 0;
            }

            $currentWagerAmountBonus = isset($this->dataBonus['wagered_bonus_amount']) ?
                (float)$this->dataBonus['wagered_bonus_amount'] : 0;
            $currentWagerBonus = GeneralHelper::formatAmount($currentWagerAmountBonus + $transactionBonusSum);

            $dataUpdateBonus = [];

            $this->dataBonus['wagered_amount'] = $currentWager;
            $this->dataBonus['wagered_bonus_amount'] = $currentWagerBonus;

            $dataUpdateBonus['data'] = json_encode($this->dataBonus);

            UserBonus::where('id', $activeBonus->id)->update($dataUpdateBonus);

            $response = [
                'success' => true,
                'message' => 'Done'
            ];

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];
        }

        DB::connection('logs')->table('bonus_logs')->where('id', $rawLogId)->update([
            'status' => json_encode($response)
        ]);

        return $response;
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
        $user = $this->user;

        $lastActionGame = LastActionGame::where('user_id', $user->id)
            ->where('last_action', '>', $date)->first();

        //$transaction = $this->user->transactions()->where('created_at', '>', $date)->first();

        if (!$lastActionGame) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function getPlayedSum()
    {
        $activeBonus = $this->active_bonus;
        if ($activeBonus->activated == 1) {

            $playedSum = (float)$this->dataBonus['wagered_bonus_amount'];

//            $playedSum = -1 * $this->user->transactions()
//                    ->where('id', '>', $this->get('transaction_id'))
//                    ->where('type', 1)
//                    ->sum('bonus_sum');
            return $playedSum;
        }
        return 0;
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function getPercent()
    {
        if ($this->active_bonus->activated == 1) {
            $played_sum = $this->getPlayedSum();
            $percent = floor($played_sum / $this->dataBonus['wagered_sum'] * 100);
            return $percent;
        }
        return 0;
    }

    public function getStatus()
    {

    }

    public function setDeposit($amount)
    {
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $rawLog = DB::connection('logs')->table('bonus_logs')
            ->where('bonus_id', '=', $activeBonus->id)
            ->where('operation_id', '=', $configBonus['operation']['setDeposit'])
            ->first();

        if ($rawLog) {
            $rawLogId = $rawLog->id;
        } else {
            $rawLogId = DB::connection('logs')->table('bonus_logs')->insertGetId([
                'bonus_id' => $activeBonus->id,
                'operation_id' => $configBonus['operation']['setDeposit'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }

        try {
            $totalDeposit = isset($this->dataBonus['total_deposit']) ? (float)$this->dataBonus['total_deposit'] : 0;

            $this->dataBonus['total_deposit'] = GeneralHelper::formatAmount($totalDeposit + (float)$amount);

            //update status deposit check
            if ($this->dataBonus['total_deposit'] >= $this->minDeposit) {
                if (!isset($this->dataBonus['wagered_deposit']) or (int)$this->dataBonus['wagered_deposit'] === 0) {
                    $this->dataBonus['wagered_deposit'] = 1;
                }
            }

            UserBonus::where('id', $activeBonus->id)->update(['data' => json_encode($this->dataBonus)]);

            $response = [
                'success' => true,
                'message' => 'Done'
            ];

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
            ];
        }

        DB::connection('logs')->table('bonus_logs')->where('id', $rawLogId)->update([
            'status' => json_encode($response)
        ]);

        return $response;
    }
}