<?php
namespace App\Bonuses;

use App\User;
use App\Transaction;
use Carbon\Carbon;

abstract class Bonus
{
    public static $id;
    protected $user;
    protected $active_bonus;
    protected $data;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->active_bonus = $this->user->bonuses()->first();
    }

    public function hasBonusTransactions($minutes = 1)
    {
        $date = Carbon::now();
        $date->modify('-' . $minutes . ' minutes');

        $transaction = $this->user->transactions()->where('created_at', '>', $date)->first();

        if(!$transaction) return false;
        else return true;
    }

    public function cancel($reason = false)
    {
        if($this->hasBonusTransactions()) throw new \Exception('Unable cancel bonus while playing. Try in several minutes.');

        $transaction = new Transaction();
        $transaction->bonus_sum = -1 * $this->user->bonus_balance;
        $transaction->sum = 0;
        $transaction->comment = $reason;
        $transaction->type = 6;
        $transaction->user()->associate($this->user);

        $this->user->changeBalance($transaction);

        if($this->user->bonus_balance == 0)
            $this->active_bonus->delete();
    }

    public function close()
    {
        if($this->hasBonusTransactions()) return false;

        $now = Carbon::now();

        if($this->active_bonus->expires_at->format('U') < $now->format('U')) $this->cancel('Expired');
        if($this->active_bonus->activated == 1 and $this->user->bonus_balance == 0 and $this->user->free_spins == 0) $this->cancel('No bonus funds');

        if($this->active_bonus->activated == 1) {
            if ($this->getPlayedSum() >= $this->get('wagered_sum')) {
                $transaction = new Transaction();
                $transaction->bonus_sum = -1 * $this->user->bonus_balance;
                $transaction->sum = $this->user->bonus_balance;
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

    public function get($var)
    {
        $data = $this->active_bonus->data;

        if(isset($data[$var])) return $data[$var];
        else throw new \Exception('Var not found');
    }

    public function set($var, $value)
    {
        if(!$this->active_bonus) throw new \Exception('Activate_bonus not found');

        $data = $this->active_bonus->data;
        $data[$var] = $value;
        $this->active_bonus->data = $data;

        $this->active_bonus->save();

        return $value;
    }

    abstract public function getStatus();
    abstract public function activate();
    abstract public function realActivation();
    abstract public function getPercent();
    abstract public function getPlayedSum();
}