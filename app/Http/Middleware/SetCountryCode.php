<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use Closure;

class SetCountryCode
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
        try
        {
            if(!$request->session()->has('iso_code'))
            {
                if(isset($_SERVER['HTTP_CF_CONNECTING_IP']))
                {
                    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
                }
                else
                {
                    $ip = $request->server('REMOTE_ADDR');
                }

                $ip = geoip($ip);

                session(['iso_code' => $ip['iso_code']]);
            }
        }
        catch (\Exception $e)
        {
            session(['iso_code' => '']);
        }

        return $next($request);
    }
}
