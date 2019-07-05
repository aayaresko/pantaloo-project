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
    public function handle($request, Closure $next)
    {
        if ($request->filled('ref') && $request->isMethod('GET')) {
            $url = $request->getBaseUrl() . '?utm_source=partner_a&utm_medium=affiliate&utm_campaign=' . $request->input('ref') . '&ref=' . $request->input('ref');
            return redirect($url);
        }
        return $next($request);
    }
}
