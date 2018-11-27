<?php

namespace Helpers;

class GeneralHelper
{
    /**
     * @param $amount
     * @return float
     */
    static public function formatAmount($amount)
    {
        return round($amount, 5, PHP_ROUND_HALF_DOWN);
    }
}