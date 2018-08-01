<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IpCheck
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
        $ip = $request->ip();
        $ips = ['51.254.21.174', '188.227.174.150'];

        if (!in_array($ip, $ips))
        {
            return redirect('/');
        }

        return $next($request);
    }
}
