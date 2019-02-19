<?php

namespace App\Bonuses;

use DB;
use App\BonusLog;
use Carbon\Carbon;
use App\UserBonus;
use App\Transaction;
use App\Models\GamesList;
use App\Bonus as BonusModel;
use \Illuminate\Http\Request;
use App\Modules\Games\PantalloGamesSystem;

class FreeSpins extends \App\Bonuses\Bonus
{
    public static $id = 3;
    protected $maxAmount = 60;
    protected $playFactor = 33;
    protected $expireDays = 1;
    protected $freeSpins = 50;
    protected $typeGames = [10001];
    protected $timeActiveBonusDays = 5;

    /**
     * @return bool
     */
    public function bonusAvailable()
    {
        $user = $this->user;
        $createdUser = $user->created_at;
        $timeActiveBonusSec = strtotime("$this->expireDays day", 0);
        $allowedDate = $createdUser->modify("+$timeActiveBonusSec second");
        $currentDate = new Carbon();

        if ($allowedDate < $currentDate) {
            return false;
        }

        $countBonuses = $this->user->bonuses()->withTrashed()->count();
        if ($countBonuses > 0) {
            return false;
        }

        return true;
    }


    public function activate()
    {
        DB::beginTransaction();
        try {
            $response = [
                'success' => true,
                'message' => 'Done'
            ];
            $user = $this->user;
            $configBonus = config('bonus');

            $createdUser = $user->created_at;
            $allowedDate = $createdUser->modify("+$this->timeActiveBonusDays days");
            $currentDate = new Carbon();

            if ($this->active_bonus) {
                throw new \Exception('You already use bonus.');
            }

            if ($this->user->bonuses()->withTrashed()->count() > 0) {
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
                'activated' => 0,
                'expires_at' => $date,
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
            ]);

            //get all games for free
            $request = new Request;

            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            $freeRoundGames = GamesList::select(['id', 'system_id'])->where([
                ['free_round', '=', 1]
            ])->get()->toArray();

            $gamesIds = implode(',', array_map(function ($item) {
                return $item['system_id'];
            }, $freeRoundGames));

            $request->merge(['gamesIds' => '12545']);
            $request->merge(['available' => 1]);
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

            throw new \Exception($errorMessage);
        }
        DB::commit();

        return true;
    }

    public function realActivation()
    {
        DB::beginTransaction();
        try {
            $response = [
                'success' => true,
                'message' => 'Done'
            ];
            $configBonus = config('bonus');
            $activeBonus = $this->active_bonus;

            if ($activeBonus->activated == 1) {
                throw new \Exception('Bonus is already activated');
            }

            //to define start transaction wagered
            $transaction = $this->user->transactions()->where([
                ['type', '=', 10],
            ])->orderBy('id', 'DESC')->first();

            if (is_null($transaction)) {
                throw new \Exception('No transactions yet with type 10');
            }

            $now = Carbon::now();

            if ($now->format('U') - $transaction->created_at->format('U') > 60) {

                $free_spin_win = $this->user->transactions()->where('type', 10)->sum('bonus_sum');

                $this->set('free_spin_win', $free_spin_win);
                $this->set('wagered_sum', $free_spin_win * $this->playFactor);
                $this->set('transaction_id', $transaction->id);

                $this->active_bonus->activated = 1;
                $this->active_bonus->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
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
                'operation_id' => $configBonus['operation']['active']
            ],
            ['status' => json_encode($response)]
        );

        return true;
    }

    public function close()
    {
        if ($this->hasBonusTransactions()) {
            throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
        }

        $now = Carbon::now();
        $user = $this->user;

        if ($this->active_bonus->expires_at->format('U') < $now->format('U')) {
            $this->cancel('Expired');
        }
        if ($this->active_bonus->activated == 1 and $this->user->bonus_balance == 0) {
            $this->cancel('No bonus funds');
        }

        if ($this->active_bonus->activated == 1) {
            if ($this->getPlayedSum() >= $this->get('wagered_sum')) {
                $transaction = new Transaction();
                $winAmount = $user->bonus_balance;
                if ((float)$winAmount > $this->maxAmount) {
                    $winAmount = $this->maxAmount;
                }
                $transaction->bonus_sum = -1 * $user->bonus_balance;
                $transaction->sum = $winAmount;
                $transaction->comment = 'Bonus to real transfer';
                $transaction->type = 7;
                $transaction->user()->associate($this->user);


                $this->user->changeBalance($transaction);

                $this->active_bonus->delete();

                $this->user->bonus_balance = 0;
                $this->user->save();
            }
        }
    }

    public function cancel($reason = false)
    {
        if ($this->hasBonusTransactions()) {
            throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');
        }

        $transaction = new Transaction();
        $transaction->bonus_sum = -1 * $this->user->bonus_balance;
        $transaction->sum = 0;
        $transaction->comment = $reason;
        $transaction->type = 6;
        $transaction->user()->associate($this->user);

        $this->user->changeBalance($transaction);

        if ($this->user->bonus_balance == 0) {
            $this->active_bonus->delete();
        }


        //to do cancel
        //to write to db
    }


    public function hasBonusTransactions($minutes = 1)
    {
        $date = Carbon::now();
        $date->modify('-' . $minutes . ' minutes');

        $transaction = $this->user->transactions()->where('created_at', '>', $date)->first();

        if (!$transaction) {
            return false;
        } else {
            return true;
        }
    }

    public function getPlayedSum()
    {
        if ($this->active_bonus->activated == 1) {
            return -1 * $this->user->transactions()
                    ->where('id', '>', $this->get('transaction_id'))
                    ->where('type', 1)
                    ->sum('bonus_sum');
        }
        return 0;
    }

    public function getStatus()
    {

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
}