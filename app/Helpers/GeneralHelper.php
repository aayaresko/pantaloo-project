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
        $amount = (float)$amount;
        $accuracyValues = config('app.accuracyValues');
        return round($amount, $accuracyValues, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @return string
     */
    static public function fullRequest()
    {
        $request = url('/') . $_SERVER['REQUEST_URI'];
        return $request;
    }

    /**
     * @return array
     */
    static public function getListLanguage()
    {
        $dir = '../resources/lang';
        $languagesIndex = array_diff(scandir($dir), ['..', '.', '.gitignore', 'example']);
        $languages = array_values($languagesIndex);
        return $languages;
    }
}