<?php

namespace App\Http\Middleware;

use Closure;
use Helpers\GeneralHelper;
use App\Http\Requests\Request;

class SetCountryCode
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
        try {
            if (!$request->session()->has('iso_code')) {

                $ip = GeneralHelper::visitorIpCloudFlare();

                //to do this job edit session way
                $ip = geoip($ip);

                session(['iso_code' => $ip['iso_code']]);
            }
        } catch (\Exception $e) {
            session(['iso_code' => '']);
        }

        return $next($request);
    }
}
