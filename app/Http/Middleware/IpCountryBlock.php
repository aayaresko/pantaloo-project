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
        $ip = GeneralHelper::visitorIpCloudFire();

        //TO DO LIBRARY OR HALPER
        $iso_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
//        if ($iso_code == 'XX') {
//            $iso_code = \geoip($ip)['iso_code'];
//        }

        $registrationStatus = !in_array($iso_code, ['US', 'UA', 'CA', 'IL', 'XX']) ? 1 : 0;

        View::share('registrationStatus', $registrationStatus);

        return $next($request);
    }
}
