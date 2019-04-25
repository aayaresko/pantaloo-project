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

        if (!$transaction) {
            return false;
        } else {
            return true;
        }
    }

    public function get($var)
    {
        $data = $this->active_bonus->data;

        if (isset($data[$var])) {
            return $data[$var];
        } else {
            throw new \Exception('Var not found');
        }
    }

    public function set($var, $value)
    {
        if (!$this->active_bonus) {
            throw new \Exception('Activate_bonus not found');
        }

        $data = $this->active_bonus->data;
        $data[$var] = $value;
        $this->active_bonus->data = $data;

        $this->active_bonus->save();

        return $value;
    }


    abstract public function cancel();

    abstract public function close();


    abstract public function getStatus();

    abstract public function activate();

    abstract public function realActivation();

    abstract public function getPercent();

    abstract public function getPlayedSum();

    abstract public function bonusAvailable();
}