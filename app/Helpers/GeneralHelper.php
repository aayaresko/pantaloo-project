<?php

namespace Helpers;

use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class GeneralHelper
{
    /**
     * @param $amount
     * @return float
     */
    public static function formatAmount($amount)
    {
        $amount = (float)$amount;
        $accuracyValues = config('app.accuracyValues');

        return round($amount, $accuracyValues, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @return mixed
     */
    public static function getAccuracyValues()
    {
        $accuracyValues = config('app.accuracyValues');

        return $accuracyValues;
    }

    /**
     * @return string
     */
    public static function fullRequest()
    {
        $request = url('/') . $_SERVER['REQUEST_URI'];

        return $request;
    }

    /**
     * @param null $key
     * @return array
     * @throws \Exception
     */
    static public function getListLanguage($key = null)
    {
        $defaultLang = ['en'];
        $modeTrans = config('translator.source');

        if (!is_null($key)) {
            $modeTrans = $key;
        }

        try {
            switch ($modeTrans) {
                case 'database':
                    $languages = Language::get()->pluck('locale')->toArray();
                    break;
                case 'files':
                    //to do read from config
                    $dir = base_path() . '/resources/lang';
                    $languagesIndex = array_diff(scandir($dir), ['..', '.']);
                    $languagesConfig = config('translator.available_locales');
                    $languages = array_values($languagesIndex);

                    if ($languagesConfig != $languages) {
                        throw new \Exception('SET CONFIG LANG VALUE');
                    }
                    break;
                case 'mixed':
                    //to do
                    $languages = $defaultLang;
                    break;
                default:
                    $languages = $defaultLang;
            }
        } catch (\Exception $ex) {
            $languages = [];
        }


        return $languages;
    }

    /**
     * @return string
     */
    public static function generateToken()
    {
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        return $token;
    }

    /**
     * Return user ip from CloudFlare headers if set.
     *
     * @return string
     */
    public static function visitorIpCloudFlare()
    {
        return isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Request::ip();
    }

    /**
     * Return country iso code from CloudFlare.
     *
     * @return string
     */
    public static function visitorCountryCloudFlare()
    {
        return isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? $_SERVER['HTTP_CF_IPCOUNTRY'] : 'XX';
    }

    /**
     * @param $transactions
     * @param $cpumBtcLimit
     * @return array
     */
    public static function statistics($transactions, $cpumBtcLimit)
    {
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        $stat = [
            'deposits' => 0,
            'pending_deposits' => 0,
            'confirm_deposits' => 0,
            'bets' => 0,
            'bet_count' => 0,
            'avg_bet' => 0,
            'wins' => 0,
            'revenue' => 0,
            'bonus' => 0,
            'profit' => 0,
            'adminProfit' => 0,
        ];

        foreach ($transactions as $transaction) {
            if ($transaction->type == 3) {
                if ((int)$transaction->confirmations < $minConfirmBtc) {
                    $stat['pending_deposits'] = $stat['pending_deposits'] + $transaction->sum;
                } else {
                    $stat['confirm_deposits'] = $stat['confirm_deposits'] + $transaction->sum;
                }

                $stat['deposits'] = $stat['deposits'] + $transaction->sum;
            } elseif ($transaction->type == 1 or $transaction->type == 2) {
                if ($transaction->type == 1) {
                    $stat['bets'] = $stat['bets'] + (-1) * $transaction->sum;
                    $stat['bet_count'] = $stat['bet_count'] + 1;
                } else {
                    $stat['wins'] = $stat['wins'] + $transaction->sum;
                }

                $stat['bonus'] = $stat['bonus'] + $transaction->bonus_sum;
                //added 0.82 koef (100% - casino fit)
                $stat['revenue'] = $stat['revenue'] + (-1) * $transaction->sum * 0.82;
                //added 0.82 koef (100% - casino fit)
                $stat['profit'] = $stat['profit'] + (-1) * $transaction->sum  * 0.82 *
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

    /**
     * to do in another place for this method.
     *
     * @param $prefixLang
     * @param $cookieLang
     * @param $currentLocale
     * @return mixed
     */
    public static function getLang($prefixLang, $cookieLang, $currentLocale)
    {
        $lang = $currentLocale;

        if (!is_null($prefixLang)) {
            $lang = $prefixLang;
        } else {
            if (!is_null($cookieLang)) {
                $lang = $cookieLang;
            } else {
                //check ip address and check language
                //if difference then ask message with gow language select
                //and set language above
                //if we don't have this language we use en
            }
        }

        return $lang;
    }

    public static function isTestMode()
    {
        return Cookie::get('testmode', false) || Request::get('test') == 1;
    }

    public static function getBTCAddressPattern()
    {
        return self::isTestMode() ? "^(2|m|n|tb1)[a-km-zA-HJ-NP-Z0-9]{25,39}$" : "^(1|3|bc1)[a-km-zA-HJ-NP-Z0-9]{25,39}$";
    }

    public static function isSecureProtocol()
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;

        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {

            $isSecure = true;
        }

        return $isSecure;
    }

    public static function getTranslateDataByFileName($filename)
    {
        $return = [];

        $filename = str_replace(DIRECTORY_SEPARATOR, ':', $filename);

        if (preg_match("/(?<lang>\w+):(?<file>\w+)\.php$/", $filename, $matches)) {
            $lang = $matches['lang'];
            $file = $matches['file'];

            $datafile = 'lang' . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $file . '.data';

            if (\Illuminate\Support\Facades\Storage::exists($datafile)) {
                $return = unserialize(\Illuminate\Support\Facades\Storage::get($datafile));
            }
        }

        return $return;
    }
}
