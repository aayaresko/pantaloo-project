<?php

namespace Helpers;

use App\User;
use Illuminate\Support\Facades\Auth;

class IntercomHelper
{
    public static function getIntercomConfig()
    {

        if (\Illuminate\Support\Facades\Auth::check()) {
            $intercom = self::getIntercomConfigByUser(Auth::user());
        } else {
            $countryCode = \Helpers\GeneralHelper::visitorCountryCloudFlare();
            $intercom = self::getIntercomConfigByCountryCode($countryCode);
        }

        return $intercom;
    }

    public static function getIntercomConfigByUser(User $user)
    {
        return self::getIntercomConfigByCountryCode($user->country);
    }

    public static function getIntercomConfigByCountryCode($countryCode)
    {

        $intercom_country = \Illuminate\Support\Facades\DB::table('intercom_country')->where('code', mb_strtoupper($countryCode))->first();

        $intercomId = $intercom_country ? $intercom_country->intercom_id : 1;

        $intercom = \Illuminate\Support\Facades\DB::table('intercom')->find($intercomId);

        $intercom = $intercom ? $intercom : \Illuminate\Support\Facades\DB::table('intercom')->find(1);

        return $intercom;
    }
}
