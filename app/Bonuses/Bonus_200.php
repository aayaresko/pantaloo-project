<?php

namespace App\Bonuses;

// Second deposit

class Bonus_200 extends Bonus_100
{
    public static $id = 2;

    protected $percent = 110;

    protected $minSum = 3;

    protected $maxSum = 0;

    protected $depositsCount = 1;

    protected $playFactor = 40;

    protected $expireDays = 30;

    protected $timeActiveBonusDays = 30;

    protected $maxAmount = 1000;
}
