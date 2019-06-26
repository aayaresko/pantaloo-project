<?php

namespace App\Http\Middleware;

use Closure;

class UtmByRef
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
        if ($request->has('ref')){
            $url = $request->getBaseUrl() . '?utm_source=partner_a&utm_medium=affiliate&utm_campaign=' . $request->input('ref');
            //return redirect($url);
        }
        return $next($request);
    }
}
