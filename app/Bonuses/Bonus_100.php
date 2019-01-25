<?php
namespace App\Bonuses;

use App\Bonus;
use App\Transaction;
use App\UserBonus;
use Carbon\Carbon;

class Bonus_100 extends \App\Bonuses\Bonus
{
    public static $id = 1;
    protected $procent = 200;
    protected $min_sum = 0;
    protected $max_sum = 600;
    protected $deposits_count = 0;
    protected $play_factor = 40;
    protected $expire_days = 14;


    public function activate()
    {
        if($this->active_bonus) throw new \Exception('You already use bonus');
        if($this->user->transactions()->deposits()->count() != $this->deposits_count) throw new \Exception('You can\'t use this bonus');
        if($this->user->bonuses()->where('bonus_id', static::$id)->withTrashed()->count() > 0) throw new \Exception('You already used this bonus');

        $date = Carbon::now();
        $date->modify('+' . $this->expire_days . 'days');

        $bonus = new UserBonus();
        $bonus->user()->associate($this->user);
        $bonus->activated = 0;
        $bonus->expires_at = $date;
        $bonus->bonus()->associate(Bonus::findOrFail(static::$id));
        $bonus->save();

        $this->active_bonus = $bonus;
    }

    public function getStatus()
    {
        if($this->active_bonus->activated == 0) return "Waiting of deposit";
        else return "Bonus wagering";
    }

    public function getPlayedSum()
    {
        if($this->active_bonus->activated == 1) {
            return -1 * $this->user->transactions()->where('id', '>', $this->get('transaction_id'))->where('type', 1)->sum('bonus_sum');
        }

        return 0;
    }

    public function getPercent()
    {
        if($this->active_bonus->activated == 1)
        {
            $played_sum = $this->getPlayedSum();

            return floor($played_sum/$this->get('wagered_sum')*100);
        }
        else return 0;
    }

    public function getBonusDeposit()
    {
        $deposit = $this->user->transactions()->deposits()->orderBy('id')->first();

        return $deposit;
    }

    public function realActivation()
    {
        if($this->active_bonus->activated == 1) return true;

        $deposit = $this->getBonusDeposit();

        if($deposit)
        {
            if($deposit->sum > $this->max_sum or $deposit->sum < $this->min_sum) $this->cancel('Invalid deposit sum');
            else {
                $transaction = new Transaction();
                $transaction->sum = 0;
                $transaction->bonus_sum = $deposit->sum * ($this->procent/100);
                $transaction->type = 5;
                $transaction->comment = 'Bonus activation';
                $transaction->user()->associate($this->user);

                $transaction = $this->user->changeBalance($transaction);

                $this->set('transaction_id', $transaction->id);
                $this->set('wagered_sum', $this->play_factor*$deposit->sum);

                $this->active_bonus->activated = 1;
                $this->active_bonus->save();
            }
        }
    }
}