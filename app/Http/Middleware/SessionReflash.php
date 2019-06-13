<?php

namespace App\Http\Middleware;

use Closure;

class SessionReflash
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
        $request->session()->reflash();

        return $next($request);
    }
}
