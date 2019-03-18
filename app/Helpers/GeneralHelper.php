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
        $dir = base_path() . '/resources/lang';
        $languagesIndex = array_diff(scandir($dir), ['..', '.']);
        $languages = array_values($languagesIndex);
        return $languages;
    }

    /**
     * @return string
     */
    static public function generateToken()
    {
        $token = hash_hmac('sha256', str_random(40), config('app.key'));
        return $token;
    }

    /**
     * @return mixed
     */
    static public function visitorIpCloudFire()
    {
        if(isset($_SERVER['HTTP_CF_CONNECTING_IP']))
        {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}