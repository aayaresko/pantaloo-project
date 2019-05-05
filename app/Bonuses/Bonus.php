<?php

namespace App\Bonuses;

use App\User;
use Carbon\Carbon;
use App\UserBonus;
use App\Transaction;
use App\Models\LastActionGame;

abstract class Bonus
{
    public static $id;

    protected $user;
    protected $lastAction;
    protected $active_bonus;
    protected $data;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->active_bonus = $this->user->bonuses()->first();
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


    abstract public function activate();

    abstract public function realActivation($params);

    abstract public function cancel();

    abstract public function close($mode);


    abstract public function hasBonusTransactions($minutes);


    abstract public function getStatus();


    abstract public function getPercent();

    abstract public function getPlayedSum();

    abstract public function bonusAvailable();
}