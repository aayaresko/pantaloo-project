<?php

namespace App\Http\Middleware;

use Closure;

class UtmByRef
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public const GET_PARAM_KEY = 'utmbr';

    public function handle($request, Closure $next)
    {
        if ($request->filled('ref') && !$request->filled(self::GET_PARAM_KEY) && $request->isMethod('GET')) {
            $url = $request->getBaseUrl() . '?' . self::GET_PARAM_KEY . '=1&utm_source=partner_a&utm_medium=affiliate&utm_campaign=' . $request->input('ref') . '&ref=' . $request->input('ref');
            return redirect($url);
        }
        return $next($request);
    }
}
