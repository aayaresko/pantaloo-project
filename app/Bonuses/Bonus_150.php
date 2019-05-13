<?php

namespace App\Bonuses;

# Second deposit

class Bonus_150 extends Bonus_100
{
    public static $id = 3;

    protected $percent = 80;
    protected $minSum = 3;
    protected $maxSum = 0;
    protected $depositsCount = 2;
    protected $playFactor = 50;
    protected $expireDays = 30;
    protected $timeActiveBonusDays = 30;
    protected $maxAmount = 1500;

}