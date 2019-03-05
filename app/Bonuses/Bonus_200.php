<?php

namespace App\Bonuses;

# Second deposit

class Bonus_200 extends Bonus_100
{
    public static $id = 2;
    protected $percent = 200;
    protected $minSum = 3;
    protected $maxSum = 0;
    protected $depositsCount = 1;
    protected $playFactor = 33;
    protected $expireDays = 30;
}