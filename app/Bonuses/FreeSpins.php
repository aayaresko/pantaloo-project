<?php

namespace App\Bonuses;

use Carbon\Carbon;
use App\UserBonus;

class FreeSpins extends \App\Bonuses\Bonus
{
    public static $id = 3;
    protected $max_sum = 100;
    protected $play_factor = 40;
    protected $expire_days = 14;
    protected $free_spins = 50;

    public function getPercent()
    {
        if ($this->active_bonus->activated == 1) {
            $played_sum = $this->getPlayedSum();

            return floor($played_sum / $this->get('wagered_sum') * 100);
        } else {
            return 0;
        }
    }

    public function getPlayedSum()
    {
        if ($this->active_bonus->activated == 1) {
            return -1 * $this->user->transactions()
                    ->where('id', '>', $this->get('transaction_id'))->where('type', 1)->sum('bonus_sum');
        }
        return 0;
    }

    public function getStatus()
    {

    }

    public function activate()
    {
        $user = $this->user;
        $configBonus = config('bonus.freeSpins');
        $timeActiveBonusSeconds = $configBonus['afterRegistrationActive'];
        $createdUser = $user->created_at;
        $allowedDate = $createdUser->modify("+$timeActiveBonusSeconds second");
        $currentDate = new Carbon();

        if ($this->active_bonus) {
            throw new \Exception('You already use bonus');
        }

        if ($this->user->transactions()->deposits()->count() > 0) {
            throw new \Exception('This bonus available only before deposit');
        }

        if ($this->user->bonuses()->withTrashed()->count() > 0) {
            throw new \Exception('You can\'t use this bonus');
        }

        if ((int)$user->email_confirmed === 0) {
            throw new \Exception('Your email is not confirm');
        }

        if ($allowedDate > $currentDate) {
            throw new \Exception('You can\'t use this bonus. Read terms.');
        }


        $date = Carbon::now();
        $date->modify('+' . $this->expire_days . 'days');

        $bonus = new UserBonus();
        $bonus->user()->associate($this->user);
        $bonus->activated = 0;
        $bonus->expires_at = $date;
        $bonus->bonus()->associate(\App\Bonus::findOrFail(static::$id));
        $bonus->save();

        $this->active_bonus = $bonus;

        $this->user->free_spins = $this->free_spins;
        $this->user->save();
    }

    public function realActivation()
    {
        if ($this->active_bonus->activated == 1) return true;

        if ($this->user->free_spins == 0) {

            $transaction = $this->user->transactions()
                ->whereIn('type', [9, 10])->orderBy('id', 'DESC')->first();

            $now = Carbon::now();

            if ($now->format('U') - $transaction->created_at->format('U') > 60) {
                if (!$transaction) {
                    throw new \Exception('Transaction not found');
                }

                $free_spin_win = $this->user->transactions()->where('type', 10)->sum('bonus_sum');

                if ($free_spin_win < 1) {
                    $free_spin_win = 1;
                }

                $this->set('free_spin_win', $free_spin_win);
                $this->set('wagered_sum', $free_spin_win * $this->play_factor);
                $this->set('transaction_id', $transaction->id);

                $this->active_bonus->activated = 1;
                $this->active_bonus->save();
            }
        }
    }

    /**
     * @return bool
     */
    public function bonusAvailable()
    {
        $user = $this->user;
        $configBonus = config('bonus.freeSpins');
        $timeActiveBonusSeconds = $configBonus['afterRegistrationActive'];
        $createdUser = $user->created_at;
        $allowedDate = $createdUser->modify("+$timeActiveBonusSeconds second");
        $currentDate = new Carbon();

        if ($allowedDate > $currentDate) {
            return false;
        }

        if ($this->user->bonuses()->withTrashed()->count() > 0) {
            return false;
        }

        return true;
    }
}