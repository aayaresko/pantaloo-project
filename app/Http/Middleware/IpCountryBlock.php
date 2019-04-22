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
    	$ip = GeneralHelper::visitorIpCloudFire();

//    	if($ip and $ip == '188.239.72.9')
    	if($ip)
	    {
		    $geo2 = geoip($ip);

		    if($geo2 and isset($geo2->iso_code) and in_array($geo2->iso_code, ['US','UA','IL']))
		    {
			    return abort(403);
		    }
	    }

        return $next($request);
    }
}
