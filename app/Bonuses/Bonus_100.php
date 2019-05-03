<?php

namespace App\Bonuses;

use DB;
use App\User;
use App\Bonus;
use App\BonusLog;
use App\UserBonus;
use Carbon\Carbon;
use App\Transaction;
use App\Models\GamesList;
use Helpers\GeneralHelper;
use App\Models\LastActionGame;
use App\Models\SystemNotification;
use App\Modules\Games\PantalloGamesSystem;
use App\Models\Pantallo\GamesPantalloSessionGame;

class Bonus_100 extends \App\Bonuses\Bonus
{
    public static $id = 4;
    public static $maxAmount = 1000;
    protected $percent = 100;
    protected $minSum = 3;
    protected $maxSum = 0;
    protected $depositsCount = 3;
    protected $playFactor = 33;
    protected $expireDays = 30;
    protected $timeActiveBonusDays = 30;

    /**
     * @return bool
     */
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

        //hide if deposit count
        $notificationTransactionDeposits = SystemNotification::where('user_id', $user->id)
            ->where('type_id', 1)
            ->count();

        if ($notificationTransactionDeposits > $this->depositsCount) {
            return false;
        }

//        if ($user->transactions()->deposits()->count() > $this->depositsCount) {
//            return false;
//        }
        //hide if deposit count

        $countBonuses = $this->user->bonuses()
            ->where('bonus_id', static::$id)->withTrashed()->count();

        if ($countBonuses > 0) {
            return false;
        }

        return true;
    }

    public function activate()
    {
        $response = [
            'success' => true,
            'message' => 'Done'
        ];

        $user = $this->user;
        $configBonus = config('bonus');

        $createdUser = $user->created_at;
        $allowedDate = $createdUser->modify("+$this->timeActiveBonusDays days");
        $currentDate = new Carbon();

        DB::beginTransaction();
        try {
            if ($allowedDate < $currentDate) {
                throw new \Exception('You can\'t use this bonus. Read terms.');
            }

            if ($this->active_bonus) {
                throw new \Exception('You already use bonus');
            }

            $notificationTransactionDeposits = SystemNotification::where('user_id', $user->id)
                ->where('type_id', 1)
                ->count();

            if ($notificationTransactionDeposits != ($this->depositsCount - 1)) {
                throw new \Exception('You can\'t use this bonus');
            }

//            if ($user->transactions()->deposits()->count() != ($this->depositsCount - 1)) {
//                throw new \Exception('You can\'t use this bonus');
//            }

            if ($user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count() > 0) {
                throw new \Exception('You already used this bonus');
            }

            $date = $user->created_at;
            $date->modify('+' . $this->expireDays . 'days');

            $bonus = new UserBonus();
            $bonus->user()->associate($user);
            $bonus->activated = 0;
            $bonus->expires_at = $date;
            $bonus->bonus()->associate(Bonus::findOrFail(static::$id));
            $bonus->save();


            BonusLog::updateOrCreate(
                [
                    'bonus_id' => $bonus->id,
                    'operation_id' => $configBonus['operation']['active']
                ],
                ['status' => json_encode($response)]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            throw new \Exception($errorMessage);
        }
        DB::commit();

        return true;
    }

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
            if ($this->active_bonus->activated != 1) {

                $deposit = $this->getBonusDeposit();

                if ($deposit) {
                    if ($deposit->sum < $this->minSum) {
                        $this->cancel('Invalid deposit sum');
                        $response = [
                            'success' => false,
                            'message' => 'Close.Invalid deposit sum'
                        ];
                    } else {
                        //TO DO round
                        $bonusSum = GeneralHelper::formatAmount($deposit->sum * ($this->percent / 100));
                        //check limit
                        if ($bonusSum > self::$maxAmount) {
                            $bonusSum = self::$maxAmount;
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

                        $this->set('transaction_id', $transaction->id);
                        $this->set('wagered_sum', $this->playFactor * $bonusSum);

                        $activeBonus->activated = 1;
                        $activeBonus->save();
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'No deposits'
                    ];
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error = 1;
            $errorCode = $e->getCode();
            $errorLine = $e->getLine();
            $errorMessage = $e->getMessage();
            $response = [
                'success' => false,
                'message' => 'Line:' . $errorLine .
                    '.Message:' . $errorMessage .
                    '.ErrorCode' . $errorCode
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
        $conditions = 0;

        DB::beginTransaction();
        try {
            if ($activeBonus->activated == 0) {
                throw new \Exception('Bonus is not activated');
            }

            if ($this->hasBonusTransactions()) {
                throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
            }

            $response = [
                'success' => true,
                'message' => 'The condition is not satisfied'
            ];

            $now = Carbon::now();
            if ($activeBonus->expires_at->format('U') < $now->format('U')) {
                $conditions = 1;
                $this->cancel('Expired');
                $response = [
                    'success' => false,
                    'message' => 'Expired'
                ];
            }

            if ($user->bonus_balance == 0) {
                $conditions = 1;
                $this->cancel('No bonus funds');
                $response = [
                    'success' => false,
                    'message' => 'No bonus funds'
                ];
            }

            if ($activeBonus->activated == 1 and $conditions === 0) {
                //to do is be new play gaming then go way down!!!!!!!!!!!!
                if ($this->getPlayedSum() >= $this->get('wagered_sum')) {
                    $transaction = new Transaction();
                    $transaction->bonus_sum = -1 * $user->bonus_balance;
                    $transaction->sum = $user->bonus_balance;
                    $transaction->comment = 'Bonus to real transfer';
                    $transaction->type = 7;
                    $transaction->user()->associate($user);
                    $transaction->save();

                    $winAmount = $user->bonus_balance;
                    User::where('id', $user->id)->update([
                        'balance' => DB::raw("balance+$winAmount"),
                        'bonus_balance' => 0
                    ]);

                    $activeBonus->delete();

                    $response = [
                        'success' => true,
                        'message' => 'Done. Close'
                    ];
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


    public function cancel($reason = false)
    {
        $user = $this->user;
        $configBonus = config('bonus');
        $activeBonus = $this->active_bonus;

        DB::beginTransaction();
        try {
            //check to enters to games
//            if ($this->hasBonusTransactions()) {
//                throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
//            }

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


    public function getStatus()
    {
        if ($this->active_bonus->activated == 0) {
            return "Waiting of deposit";
        } else {
            return "Bonus wagering";
        }
    }

    public function getPlayedSum()
    {
        //TO DO - ADD to where date
        if ($this->active_bonus->activated == 1) {
            $sum = -1 * $this->user->transactions()
                    ->where('id', '>', $this->get('transaction_id'))
                    ->where('type', 1)->sum('bonus_sum');
            return $sum;
        }
        return 0;
    }

    public function getPercent()
    {
        if ($this->active_bonus->activated == 1) {
            $played_sum = $this->getPlayedSum();

            return floor($played_sum / $this->get('wagered_sum') * 100);
        } else {
            return 0;
        }
    }

    public function getBonusDeposit()
    {
        $user = $this->user;
        $depositsCount = $this->depositsCount;

        //$deposits = $this->user->transactions()->deposits()->orderBy('id')->limit($depositsCount)->get();

        $deposits = SystemNotification::where('user_id', $user->id)
            ->where('type_id', 1)->orderBy('id')->limit($depositsCount)->get();

        if (count($deposits) == $depositsCount) {
            return $deposits[$depositsCount - 1];
        } else {
            return false;
        }
    }
}
