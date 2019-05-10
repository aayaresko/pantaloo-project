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

                session(['iso_code' => GeneralHelper::visitorCountryCloudFlare()]);
            }
        } catch (\Exception $e) {
            session(['iso_code' => '']);
        }

        return $next($request);
    }
}
