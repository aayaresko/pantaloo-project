<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PageController;
use Closure;
use Helpers\GeneralHelper;
use Illuminate\Support\Facades\Route;
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

    	if($ip)
	    {
		    $geo2 = geoip($ip);

		    if($geo2 and isset($geo2->iso_code) and in_array($geo2->iso_code, $this->getClosedCountries($request)))
		    {
			    return abort(403);
		    }
	    }

        return $next($request);
    }

    private function getClosedCountries($request)
    {
		if($request->route()->parameter('partner'))
	    {
	    	return ['US'];
	    }
		else
		{
			return ['US','UA','IL'];
		}
    }
}
