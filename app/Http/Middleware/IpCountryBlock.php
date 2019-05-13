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
        $iso_code = GeneralHelper::visitorCountryCloudFlare();

        $disableRegistrationCountry = config('appAdditional.disableRegistration');

        $registrationStatus = !in_array($iso_code, $disableRegistrationCountry) ? 1 : 0;

        View::share('registrationStatus', $registrationStatus);

        return $next($request);
    }
}
