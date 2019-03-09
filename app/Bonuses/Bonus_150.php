<?php

namespace App\Bonuses;

# Second deposit

class Bonus_150 extends Bonus_100
{
    public static $id = 3;
    protected $percent = 200;
    protected $minSum = 3;
    protected $maxSum = 0;
    protected $depositsCount = 2;
    protected $playFactor = 33;
    protected $expireDays = 30;

}