<?php

namespace App\Http\Middleware;

use Closure;
use Helpers\GeneralHelper;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\View;

class IpCountryBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $disableRegistrationCountry = config('appAdditional.disableRegistration');
        $iso_code = GeneralHelper::visitorCountryCloudFlare();
        $ip =  GeneralHelper::visitorIpCloudFlare();

        $registrationStatus = !in_array($iso_code, $disableRegistrationCountry) || $ip == '127.0.0.1' || GeneralHelper::isTestMode() ? 1 : 0;

        View::share('registrationStatus', $registrationStatus);

        return $next($request);
    }
}
