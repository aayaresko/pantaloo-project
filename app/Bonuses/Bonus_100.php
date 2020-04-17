<?php

namespace App\Bonuses;

use App\Models\SystemNotification;
use DB;
use App\User;
use App\UserBonus;
use Carbon\Carbon;
use App\Transaction;
use Helpers\GeneralHelper;
use App\Bonus as BonusModel;
use App\Events\OpenBonusEvent;
use App\Models\LastActionGame;
use App\Events\CloseBonusEvent;
use App\Events\BonusCancelEvent;
use App\Events\BonusDepositEvent;
use App\Modules\Others\DebugGame;
use App\Events\DepositWagerDoneEvent;

class Bonus_100 extends \App\Bonuses\Bonus
{
    public static $id = 4;

    protected $percent = 55;

    protected $minSum = 3;

    protected $maxSum = 0;

    protected $depositsCount = 3;

    protected $playFactor = 40;

    protected $expireDays = 30;

    protected $timeActiveBonusDays = 30;

    protected $maxAmount = 2000;

    protected $minActivationBonusBalance = 0.1;

    const SPECIAL = 1313;

    public function checkDepositsCountMatch()
    {
        $user = $this->user;

        if (!$user instanceof User) {
            return true;
        }

        $depositTransactions = Transaction::deposits()
            ->where('user_id', $user->id)
            ->count();

        return $depositTransactions === ($this->depositsCount - 1);
    }

    // use since 17.04.2020
    public function bonusAvailable($params = [])
    {
        $user = $this->user;
        $mode = 0;
        if (isset($params['mode'])) {
            $mode = $params['mode'];
        }

        //GENERAL check****
        $bonusInfo = BonusModel::where('id', static::$id)->where('public', 1)->first();

        if (is_null($bonusInfo)) {
            return false;
        }
        //GENERAL check****

        //additional check****
        if ($mode == 0) {
            //check if user isset
            if (!is_null($user)) {
                // bonus should not be available if a user activates other bonus
                $bonus = $this->user->bonuses()
                    ->where('bonus_id', '!=', static::$id)
                    ->where('activated', 1)
                    ->first();

                if ($bonus instanceof UserBonus) {
                    return false;
                }

                // only for current user's deposits amount
                if (!$this->checkDepositsCountMatch()) {
                    return false;
                }

                $countBonuses = $this->user->bonuses()
                    ->where('bonus_id', static::$id)
                    ->withTrashed()
                    ->count();

                if ($countBonuses > 0) {
                    return false;
                }

                // If there is bonus balance, disable other bonus availability, unless the bonus is below 0.10
                if (1 === bccomp($this->user->bonus_balance, $this->minActivationBonusBalance, 2)) {
                    return false;
                }
            }
        }

        if (2 === $mode) {
            if (!$user instanceof User) {
                return true;
            }

            if (!$this->checkDepositsCountMatch()) {
                return false;
            }

            // has duplicated activated bonuses
            $countBonuses = $this->user->bonuses()
                ->where('bonus_id', static::$id)
                ->where('activated', 1)
                ->withTrashed()
                ->count();

            if ($countBonuses > 0) {
                return false;
            }

            // already activated bonuses found!
            $countBonuses = $this->user->bonuses()
                ->where('activated', 1)
                ->withTrashed()
                ->count();

            if ($countBonuses > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated
     * copied 17.04.2020
     */
    public function bonusAvailableOld($params = [])
    {
        $user = $this->user;
        $mode = 0;
        if (isset($params['mode'])) {
            $mode = $params['mode'];
        }

        //GENERAL check****
        $bonusInfo = BonusModel::where('id', static::$id)->where('public', 1)->first();

        if (is_null($bonusInfo)) {
            return false;
        }
        //GENERAL check****

        //additional check****
        if ($mode == 0) {
            //check if user isset
            if (!is_null($user)) {
                //hide if deposit count
                $notificationTransactionDeposits = SystemNotification::where('user_id', $user->id)
                    ->where('type_id', 1)
                    ->count();

                if ($notificationTransactionDeposits > $this->depositsCount) {
                    return false;
                }

                $countBonuses = $this->user->bonuses()
                    ->where('bonus_id', static::$id)
                    ->withTrashed()
                    ->count();

                if ($countBonuses > 0) {
                    return false;
                }
            }
        }

        return true;
    }

    public function activate($params = [])
    {
        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');

        $createdUser = $user->created_at;
        $allowedDate = $createdUser->modify("+$this->timeActiveBonusDays days");
        $currentDate = new Carbon();

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.depositBonus' . static::$id);

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
            $ipCurrent = GeneralHelper::visitorIpCloudFlare();
            $ipFormatCurrent = inet_pton($ipCurrent);

            if ($mode == 0) {
                //cancel and open
                $bonusInfo = BonusModel::where('id', static::$id)->first();
                if (is_null($bonusInfo)) {
                    throw new \Exception('Some is wrong');
                }

                if ($bonusInfo->public == 0) {
                    //close free spin temporary
                    throw new \Exception('This bonus is temporarily unavailable');
                }
                //cancel and open

                //baned country
//            if (!is_null($user->country)) {
//                $banedBonusesCountries = config('appAdditional.banedBonusesCountries');
//                if (in_array($user->country, $banedBonusesCountries)) {
//                    throw new \Exception('You cannot activate this bonus in accordance with clause 1.12 of the bonus terms & conditions.');
//                }
//            }

//            if ($allowedDate < $currentDate) {
//                throw new \Exception('You cannot activate this bonus in accordance with' .
//                    ' clause 1.6 of the bonus terms & conditions.');
//            }

                if ($this->active_bonus) {
                    if ($this->active_bonus->bonus_id != static::$id) {
                        throw new \Exception('You cannot activate this bonus as there is ' .
                            'already an active bonus.');
                    } else {
                        throw new \Exception('This bonus is already active.');
                    }
                }

                if ((int)$user->email_confirmed === 0) {
                    throw new \Exception(trans('casino.try_get_bonuses_without_confirmation_email'));
                }

                /*$notificationTransactionDeposits = SystemNotification::where('user_id', $user->id)
                    ->where('type_id', 1)
                    ->count();*/

                if (!GeneralHelper::isTestMode() && !$this->checkDepositsCountMatch()) {
                    throw new \Exception(
                        'You cannot activate this bonus in accordance with clause 3.4 and 4.4 of the bonus terms & conditions.'
                    );
                }

                if ($user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count() > 0) {
                    throw new \Exception('This bonus is already used.');
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
            }

            $date = Carbon::now();
            $date->modify('+' . $this->expireDays . 'days');

            $bonusUser = UserBonus::create([
                'user_id' => $user->id,
                'bonus_id' => static::$id,
                'data' => [
                    'wagered_sum' => 0,
                    'transaction_id' => 0,
                    'wagered_amount' => 0,
                    'wagered_bonus_amount' => 0,
                    'dateStart' => $currentDate,
                    'ip_address' => $ipCurrent,
                ],
                'ip_address' => $ipFormatCurrent,
                'activated' => 0,
                'expires_at' => $date,
            ]);

            User::where('id', $user->id)->update([
                'bonus_id' => static::$id,
            ]);

            event(new OpenBonusEvent($user, 'bonus deposit ' . $this->percent . '%'));

            $response = [
                'success' => true,
                'message' => 'Done',
                'details' => [
                    'bonus_id' => $bonusUser->id,
                    'mode' => $mode,
                ],
            ];
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => $errorMessage,
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
        $amount = (float)$params['amount'];

        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.depositBonus' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['realActivation'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        //action
        try {
            //validate
            if ((int)$activeBonus->activated === 1) {
                throw new \Exception('The bonus has already been activated real.');
            }

            //check amount float
            $deposit = $amount;

            if ($deposit < $this->minSum) {
                $cancelBonus = $this->cancel('Invalid deposit sum');
                if ($cancelBonus['success'] === false) {
                    throw new \Exception('Method cancel not working');
                } else {
                    throw new \Exception('Invalid deposit sum', self::SPECIAL);
                }
            } else {
                //TO DO round
                $bonusSum = GeneralHelper::formatAmount($deposit * ($this->percent / 100));
                //check limit

                if ($bonusSum > $this->maxAmount) {
                    $bonusSum = $this->maxAmount;
                }

                $transaction = new Transaction();
                $transaction->sum = 0;
                $transaction->bonus_sum = $bonusSum;
                $transaction->type = 5;
                $transaction->comment = 'Bonus activation';
                $transaction->user()->associate($user);
                $transaction->save();

                User::where('id', $user->id)->update([
                    'bonus_balance' => DB::raw("bonus_balance+$bonusSum"),
                ]);

                $this->dataBonus['transaction_id'] = $transaction->id;
                $this->dataBonus['wagered_sum'] = $this->playFactor * $bonusSum;

                $dataUpdateBonus['data'] = json_encode($this->dataBonus);
                $dataUpdateBonus['activated'] = 1;

                UserBonus::where('id', $activeBonus->id)->update($dataUpdateBonus);

                event(new BonusDepositEvent($user, $bonusSum));

                $response = [
                    'success' => true,
                    'message' => 'Done',
                ];
            }
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine .
                    '.Message:' . $errorMessage,
            ];

            if ($errorCode === self::SPECIAL) {
                $response['success'] = true;
            }
        }

        DB::connection('logs')->table('bonus_logs')->where('id', $rawLogId)->update([
            'status' => json_encode($response),
        ]);

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
        $whoClose = ($mode == 0) ? 'by game' : 'by balance';

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.depositBonus' . static::$id);

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

            if ($mode == 1) {
                if ($this->hasBonusTransactions()) {
                    throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
                }
            }

            if ($user->bonus_balance == 0) {
                $cancelBonus = $this->cancel('No bonus funds');
                if ($cancelBonus['success'] === false) {
                    throw new \Exception('Method cancel not working');
                } else {
                    throw new \Exception('No bonus funds', self::SPECIAL);
                }
            }

            $wageredSum = $this->dataBonus['wagered_sum'];

            if ($this->getPlayedSum() >= $wageredSum) {
                $transaction = new Transaction();
                $transaction->bonus_sum = -1 * $user->bonus_balance;
                $transaction->sum = $user->bonus_balance;
                $transaction->comment = 'Bonus to real transfer';
                $transaction->type = 7;
                $transaction->user()->associate($user);
                $transaction->save();

                $winAmount = $user->bonus_balance;
                //trim balance?**************************
                User::where('id', $user->id)->update([
                    'balance' => DB::raw("balance+$winAmount"),
                    'bonus_balance' => 0,
                    'bonus_id' => null,
                ]);

                $activeBonus->delete();

                event(new CloseBonusEvent($user, 'deposit bonus ' . $this->percent . '%'));
                event(new DepositWagerDoneEvent($user));

                $response = [
                    'success' => true,
                    'message' => 'Done. Close' . $whoClose,
                ];
            } else {
                throw new \Exception('The condition is not satisfied');
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
        $user = $this->user;
        $date = new \DateTime();
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        $userId = $user->id;
        $debugGame = new DebugGame();
        $debugGame->start();
        $rawLogKey = config('appAdditional.rawLogKey.depositBonus' . static::$id);

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => $rawLogKey + $configBonus['operation']['cancel'],
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        try {
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

            event(new BonusCancelEvent($updateUser, $this->getPercent() . '%'));

            $response = [
                'success' => true,
                'message' => 'Done',
            ];
        } catch (\Exception $e) {
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();

            $response = [
                'success' => false,
                'message' => $errorMessage,
                //'message' => 'Line:' . $errorLine . '.Message:' . $errorMessage
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
        $rawLogKey = config('appAdditional.rawLogKey.depositBonus' . static::$id);

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
                $currentWagerAmount = isset($this->dataBonus['wagered_amount']) ? (float)$this->dataBonus['wagered_amount'] : 0;
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

    public function getStatus()
    {
        if ($this->active_bonus->activated == 0) {
            return 'Waiting of deposit';
        } else {
            return 'Bonus wagering';
        }
    }

    public function getPlayedSum()
    {
        //TO DO - ADD to where date
        $activeBonus = $this->active_bonus;
        if ($activeBonus->activated == 1) {
            // TODO Lior - what is wagered_bonus_amount
            $sum = (float)$this->dataBonus['wagered_bonus_amount'];
//            $sum = -1 * $this->user->transactions()
//                    ->where('id', '>', $this->get('transaction_id'))
//                    ->where('type', 1)->sum('bonus_sum');
            return $sum;
        }

        return 0;
    }

    public function getPercent()
    {
        if ($this->active_bonus->activated == 1) {
            $played_sum = $this->getPlayedSum();

            return floor($played_sum / $this->dataBonus['wagered_sum'] * 100);
        } else {
            return 0;
        }
    }

    public function getBonusDeposit()
    {
        $user = $this->user;
        $depositsCount = $this->depositsCount;

        //$deposits = $this->user->transactions()->deposits()
        //->orderBy('id')->limit($depositsCount)->get();

        $deposits = SystemNotification::where('user_id', $user->id)
            ->where('type_id', 1)->orderBy('id')->limit($depositsCount)->get()->all();

        if (count($deposits) == $depositsCount) {
            return $deposits[$depositsCount - 1];
        } else {
            return false;
        }
    }

    public function hasBonusTransactions($minutes = 1)
    {
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
}
