<?php

namespace App\Bonuses;

use App\User;
use App\UserBonus;
use Carbon\Carbon;
use App\Transaction;
use App\Models\LastActionGame;

abstract class Bonus
{
    public static $id;

    protected $user;

    protected $lastAction;

    protected $active_bonus;

    protected $data;

    protected $dataBonus;

    public function __construct(User $user = null)
    {
        $this->user = $user;

        if (!is_null($user)) {
            // Get the current bonus
            // TODO Lior - why only the first() and not all of the bonuses, why not "latest" to get the last active one
            $this->active_bonus = $this->user->bonuses()->first();
        }

        if (! is_null($this->active_bonus)) {
            //TODO Lior - why do we need the active bonus data in a new class property?
            $this->dataBonus = $this->active_bonus->data;
        }
    }

    abstract public function bonusAvailable($params);

    abstract public function activate($params);

    abstract public function realActivation($params);

    abstract public function cancel();

    abstract public function close($mode);

    abstract public function wagerUpdate($transaction);

    abstract public function getPlayedSum();

    abstract public function hasBonusTransactions($minutes);

    abstract public function getStatus();

    abstract public function getPercent();
}
