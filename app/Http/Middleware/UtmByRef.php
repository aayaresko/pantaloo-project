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

            $params = [];

            parse_str($request->getQueryString(), $params);

            $params['utm_source'] = 'partner_a';
            $params['utm_medium'] = 'affiliate';
            $params['utm_campaign'] = $params['ref'];

            $query = http_build_query($params);

            $question = $request->getBaseUrl() . $request->getPathInfo() === '/' ? '/?' : '?';
            $redirectUrl = $query ? $request->url() . $question . $query : $request->url();

            if ($request->fullUrl() != $redirectUrl) {
                return redirect($redirectUrl);
            }
        }

        return $next($request);
    }
}
