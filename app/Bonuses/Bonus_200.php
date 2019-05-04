<?php

namespace App\Bonuses;

# Second deposit

class Bonus_200 extends Bonus_100
{
    public static $id = 2;
    public static $maxAmount = 1000;
    protected $percent = 110;
    protected $minSum = 3;
    protected $maxSum = 0;
    protected $depositsCount = 1;
    protected $playFactor = 50;
    protected $expireDays = 30;
    protected $timeActiveBonusDays = 30;
}