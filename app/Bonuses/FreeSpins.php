<?php

namespace App\Bonuses;

use DB;
use Log;
use App\User;
use App\BonusLog;
use App\UserBonus;
use Carbon\Carbon;
use App\Transaction;
use GuzzleHttp\Client;
use App\ModernExtraUsers;
use App\Models\GamesList;
use Helpers\GeneralHelper;
use App\Bonus as BonusModel;
use \Illuminate\Http\Request;
use App\Models\LastActionGame;
use App\Events\OpenBonusEvent;
use App\Events\WagerDoneEvent;
use App\Events\BonusGameEvent;
use App\Events\CloseBonusEvent;
use App\Events\BonusCancelEvent;
use App\Modules\Others\DebugGame;
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

    public function bonusAvailable($params = [])
    {
        $user = $this->user;
        $mode = 0;
        if (isset($params['mode'])) {
            $mode = $params['mode'];
        }

        //GENERAL check****
        $bonusInfo = BonusModel::where('id', static::$id)->first();

        if (is_null($bonusInfo)) {
            return false;
        }

        if (!is_null($user)) {
            //get user info by free spins
            $userFreeInfo = ModernExtraUsers::where('user_id', $user->id)->where('code', 'freeEnabled')->first();
            if (!($userFreeInfo and $userFreeInfo->value == 1)) {
                //cancel and open
                if ($bonusInfo->public == 0) {
                    //close free spin temporary
                    return false;
                }
                //cancel and open
            }
            //GENERAL check****
        } else {
            if ($bonusInfo->public == 0) {
                return false;
            }
        }


        //additional check****
        if ($mode == 0) {
            //check if user isset
            if (!is_null($user)) {
                //hide if user
                $timeActiveBonusSec = strtotime("$this->timeActiveBonusDays day", 0);

                $createdUser = $user->created_at;
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
            }
        }
        //additional check****

        return true;
    }

    public function activate($params = [])
    {
        $user = $this->user;
        $date = new \DateTime();

        $configBonus = config('bonus');
        $slotTypeId = config('appAdditional.slotTypeId');

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['active'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $mode = 0;
        if (isset($params['mode'])) {
            $mode = $params['mode'];
        }

        try {
            $createdUser = $user->created_at;
            $allowedDate = $createdUser->modify("+$this->timeActiveBonusDays days");
            $currentDate = new Carbon();

            $banedBonusesCountries = config('appAdditional.banedBonusesCountries');
            $disableRegistration = config('appAdditional.disableRegistration');
            $codeCountryCurrent = GeneralHelper::visitorCountryCloudFlare();

            $ipCurrent = GeneralHelper::visitorIpCloudFlare();
            $ipFormatCurrent = inet_pton($ipCurrent);

            if ($mode == 0) {

                $bonusInfo = BonusModel::where('id', static::$id)->first();

                if (is_null($bonusInfo)) {
                    throw new \Exception('Some is wrong');
                }

                //get user info by free spins
                $userFreeInfo = ModernExtraUsers::where('user_id', $user->id)->where('code', 'freeEnabled')->first();
                if (!($userFreeInfo and $userFreeInfo->value == 1)) {
                    //cancel and open

                    if ($bonusInfo->public == 0) {
                        //close free spin temporary
                        throw new \Exception('This bonus is temporarily unavailable');
                    }
                    //cancel and open
                }
                //get user info by free spins

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

                //baned country
                if (!GeneralHelper::isTestMode() && in_array($codeCountryCurrent,
                        array_merge($banedBonusesCountries, $disableRegistration))) {
                    throw new \Exception('You cannot activate this bonus in' .
                        ' accordance with clause 2.3 of the bonus terms & conditions.');
                }

                //baned country
                if (!GeneralHelper::isTestMode() && !is_null($user->country)) {
                    if (in_array($user->country, array_merge($banedBonusesCountries, $disableRegistration))) {
                        throw new \Exception('You cannot activate this bonus in' .
                            ' accordance with clause 2.3 of the bonus terms & conditions.');
                    }
                }

                if ((int)$user->email_confirmed === 0) {
                    throw new \Exception(trans('casino.try_get_bonuses_without_confirmation_email'));
                }

                if ($allowedDate < $currentDate) {
                    throw new \Exception('You cannot activate this bonus in accordance ' .
                        'with clause 2.2 of the bonus terms & conditions.');
                }

                //check ip
                $currentBonusByIp = UserBonus::where('bonus_id', static::$id)
                    ->where('ip_address', $ipFormatCurrent)
                    ->withTrashed()->count();

                if (!GeneralHelper::isTestMode() && $currentBonusByIp > 0) {
                    throw new \Exception('You cannot activate this bonus in accordance' .
                        ' with clause 1.18 of the bonus terms & conditions');
                }
                //check ip

                //IpQuality
                $ipQualityScoreUrl = config('appAdditional.ipQualityScoreUrl');
                $ipQualityScoreKey = config('appAdditional.ipQualityScoreKey');


                if (!GeneralHelper::isTestMode()) {
                    //5 to do config
                    $client = new Client(['timeout' => 5]);
                    //TO DO if exception
                    $responseIpQuality = $client->request('GET', $ipQualityScoreUrl . '/' . $ipQualityScoreKey . '/' . $ipCurrent);
                    $responseIpQualityJson = json_decode($responseIpQuality->getBody()->getContents(), true);

                    if (isset($responseIpQualityJson['success'])) {
                        if ($responseIpQualityJson['success'] == true) {
                            if ($responseIpQualityJson['vpn'] == true or $responseIpQualityJson['tor'] == true) {
//                                throw new \Exception('Free spins are not available while using VPN/Proxy');
                                throw new \Exception(trans('casino.free_spins_not_available_using_vpn'));
                            }
                        }
                    }
                }
                //IpQuality
            }

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
                    'ip_address' => $ipCurrent,
                ],
                'ip_address' => $ipFormatCurrent,
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
                ->groupBy('games_types_games.game_id')->get()->all();

            $gamesIds = implode(',', array_map(function ($item) {
                return $item->system_id;
            }, $freeRoundGames));

            $request->merge(['gamesIds' => $gamesIds]);
            $request->merge(['available' => $this->freeSpins]);
            $request->merge(['timeFreeRound' => strtotime("$this->expireDays day", 0)]);
            $request->merge(['mode' => $mode]); //only once

            $pantalloGamesSystem = new PantalloGamesSystem();
            $freeRound = $pantalloGamesSystem->freeRound($request);

            if ($freeRound['success'] === false) {
                throw new \Exception('Problem with provider free spins');
            }

            User::where('id', $user->id)->update([
                'bonus_id' => static::$id,
                'free_spins' => 1
            ]);

            event(new OpenBonusEvent($user, 'welcome bonus'));

            $response = [
                'success' => true,
                'message' => 'Done',
                'details' => [
                    'bonus_id' => $bonusUser->id,
                    'mode' => $mode,
                ],
            ];
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => $errorMessage,
                //'message' => 'Message:' . $errorMessage. ' Line:' . $errorLine
            ];
        }

        $debugResult = $debugGame->end();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugResult),
        ]);

        return $response;
    }

    public function realActivation($params)
    {
        $amount = $params['amount'];
        $transactionId = $params['transactionId'];

        $user = $this->user;
        $date = new \DateTime();

        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['realActivation'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

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
                'message' => 'Done',
            ];
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage,
            ];
        }

        $debugResult = $debugGame->end();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugResult),
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
        $whoClose = ($mode == 0) ? 'by game' : 'by balance';

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . self::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['close'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

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
                    'bonus_id' => null,
                ]);

                UserBonus::where('id', $activeBonus->id)->update([
                    'total_amount' => DB::raw("total_amount + $winAmount"),
                ]);

                $activeBonus->delete();

                event(new CloseBonusEvent($user, 'welcome bonus'));
                event(new WagerDoneEvent($user));

                $response = [
                    'success' => true,
                    'message' => 'Bonus to real transfer. Close:' . $whoClose,
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'The condition is not satisfied' . $whoClose,
                ];
            }
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage . $whoClose,
            ];

            if ($errorCode === self::SPECIAL) {
                $response['success'] = true;
            }
        }

        $debugResult = $debugGame->end();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugResult),
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

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['cancel'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

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
                    'bonus_balance' => DB::raw("bonus_balance+$bonusAmount"),
                    'bonus_id' => null,
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

                event(new BonusCancelEvent($updateUser, 'welcome bonus'));
                //TO DO FIX DOUBLE CODE
                $response = [
                    'success' => true,
                    'message' => 'Done',
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
                    'bonus_id' => null,
                ]);

                $updateUser = User::where('id', $user->id)->first();

                if ((float)$updateUser->bonus_balance === (float)0) {
                    $activeBonus->delete();
                }

                event(new BonusCancelEvent($updateUser, 'welcome bonus'));
                //TO DO FIX DOUBLE CODE
                $response = [
                    'success' => true,
                    'message' => 'Done.Expire',
                ];
            }
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();

            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage,
            ];
        }

        $debugResult = $debugGame->end();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugResult),
        ]);

        return $response;
    }

    public function wagerUpdate($transaction)
    {
        $transactionAmount = abs((float)$transaction['sum']);
        $transactionBonusSum = abs((float)$transaction['bonus_sum']);

        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['wagerUpdate'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        try {
            //if was be deposit
            if (isset($this->dataBonus['wagered_deposit']) and (int)$this->dataBonus['wagered_deposit'] === 1) {
                $currentWagerAmount = isset($this->dataBonus['wagered_amount']) ?
                    (float)$this->dataBonus['wagered_amount'] : 0;

                $currentWager = GeneralHelper::formatAmount($currentWagerAmount + $transactionAmount);

                $totalDeposit = (float)$this->dataBonus['total_deposit'];
                if ($totalDeposit < $currentWager) {
                    $currentWager = $totalDeposit;
                }
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
                'message' => 'Done',
            ];
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage,
            ];
        }

        $debugResult = $debugGame->end();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugResult),
        ]);

        return $response;
    }

    public function setDeposit($amount)
    {
        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['setDeposit'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        try {
            $totalDeposit = isset($this->dataBonus['total_deposit']) ? (float)$this->dataBonus['total_deposit'] : 0;

            $this->dataBonus['total_deposit'] = GeneralHelper::formatAmount($totalDeposit + (float)$amount);

            //update status deposit check
            if (!isset($this->dataBonus['wagered_deposit']) or (int)$this->dataBonus['wagered_deposit'] === 0) {
                $this->dataBonus['wagered_deposit'] = 1;
            }

            UserBonus::where('id', $activeBonus->id)->update(['data' => json_encode($this->dataBonus)]);

            $response = [
                'success' => true,
                'message' => 'Done',
            ];
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage,
            ];
        }

        $debugResult = $debugGame->end();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugResult),
        ]);

        return $response;
    }

    /**
     * check if someone is playing now.
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

    public function setGame($game, $key)
    {
        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.freeSpins' . static::$id);

        if (!isset($this->dataBonus[$key])) {
            $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
                'type_id' => $rawLogKey + $configBonus['operation']['setGame'],
                'user_id' => $userId,
                'request' => GeneralHelper::fullRequest(),
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            try {
                //set this game
                $this->dataBonus[$key] = $game->id;
                UserBonus::where('user_id', $user->id)->update([
                    'data' => json_encode($this->dataBonus),
                ]);

                //event
                event(new BonusGameEvent($user, $game->real_name));

                $response = [
                    'success' => true,
                    'message' => 'Done',
                ];
            } catch (\Exception $e) {
                $errorLine = $e->getLine();
                $errorMessage = $e->getMessage();
                $response = [
                    'success' => false,
                    'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage,
                ];
            }

            $debugResult = $debugGame->end();

            DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
                'response' => json_encode($response),
                'extra' => json_encode($debugResult),
            ]);

            return $response;
        }

        return [
            'success' => false,
            'message' => 'The game has been installed',
        ];
    }
}
