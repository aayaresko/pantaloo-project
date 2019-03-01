<?php

namespace App\Bonuses;

# Second deposit

class Bonus_200 extends Bonus_100
{
    public static $id = 2;
    protected $procent = 150;
    protected $play_factor = 50;
    protected $min_sum = 0;//150
    protected $max_sum = 300;
    protected $deposits_count = 1;

    public function getBonusDeposit()
    {
        $deposits = $this->user->transactions()->deposits()->orderBy('id')->limit(2)->get();

        if (count($deposits) == 2) {
            return $deposits[1];
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function bonusAvailable()
    {
        return true;
    }
}