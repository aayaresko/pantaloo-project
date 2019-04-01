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
     * @return mixed
     */
    static public function getAccuracyValues()
    {
        $accuracyValues = config('app.accuracyValues');
        return $accuracyValues;
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
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }


    /**
     * @param $transactions
     * @param $cpumBtcLimit
     * @return array
     */
    static public function statistics($transactions, $cpumBtcLimit)
    {
        $stat = [
            'deposits' => 0,
            'bets' => 0,
            'bet_count' => 0,
            'avg_bet' => 0,
            'wins' => 0,
            'revenue' => 0,
            'bonus' => 0,
            'profit' => 0,
            'adminProfit' => 0
        ];

        foreach ($transactions as $transaction) {

            if ($transaction->type == 3) {

                $stat['deposits'] = $stat['deposits'] + $transaction->sum;

            } elseif ($transaction->type == 1 or $transaction->type == 2) {

                if ($transaction->type == 1) {
                    $stat['bets'] = $stat['bets'] + (-1) * $transaction->sum;
                    $stat['bet_count'] = $stat['bet_count'] + 1;

                } else {
                    $stat['wins'] = $stat['wins'] + $transaction->sum;
                }

                $stat['revenue'] = $stat['revenue'] + (-1) * $transaction->sum;

                $stat['bonus'] = $stat['bonus'] + $transaction->bonus_sum;

                $stat['profit'] = $stat['profit'] + (-1) * $transaction->sum *
                    $transaction->agent_commission / 100;

                $stat['adminProfit'] = $stat['adminProfit'] + (-1) * $transaction->sum - (-1) *
                    $transaction->sum * $transaction->agent_commission / 100;
            }
        }

        if ($stat['bet_count'] != 0) {
            $stat['avg_bet'] = $stat['bets'] / $stat['bet_count'];
        }

        foreach ($stat as $key => $value) {
            $stat[$key] = round($value, 2);
        }

        $stat['cpa'] = ($stat['deposits'] >= $cpumBtcLimit) ? 1 : 0;

        $cpaPending = self::formatAmount($cpumBtcLimit - $stat['deposits']);

        $stat['cpaPending'] = ($cpaPending >= 0) ? $cpaPending : 0;

        return $stat;
    }
}