<?php

namespace App\Http\Middleware;

use Closure;
use Helpers\GeneralHelper;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Cookie;

class UtmByRef
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    const FLAG_KEY = 'utm_by_ref';

    public function handle($request, Closure $next)
    {
        if ($request->filled('ref') && $request->isMethod('GET') && !$request->has(self::FLAG_KEY)) {

            $params = [];

            parse_str($request->getQueryString(), $params);

            $ref = $request->header('Referer');

            $params['utm_source'] = $ref ? $ref : '';
            $params['utm_medium'] = 'affiliate';
            $params['utm_campaign'] = $params['ref'];
            $params[self::FLAG_KEY] = '';

            $query = http_build_query($params);

            $question = $request->getBaseUrl() . $request->getPathInfo() === '/' ? '/?' : '?';
            $redirectUrl = $query ? $request->url() . $question . $query : $request->url();

            if (GeneralHelper::isSecureProtocol()){
                $redirectUrl = preg_replace("/^http:/i", 'https:', $redirectUrl);
            }

            return redirect($redirectUrl);
        }

        return $next($request);
    }
}
