<?php

namespace App\Http\Middleware;

use Closure;
use Helpers\GeneralHelper;
use Torann\GeoIP\Facades\GeoIP;

class IpCountryBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $geo2 = geoip(GeneralHelper::visitorIpCloudFire());

	    if($geo2 and isset($geo2->iso_code) and $geo2->iso_code == 'US')
	    {
//		    return abort(403);
	    }

        return $next($request);
    }
}
