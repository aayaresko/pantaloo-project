<?php

namespace App\Http\Middleware;

use App\Domain;
use Closure;
use Illuminate\Support\Facades\Config;

class LanguageGet
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
        $parts = parse_url(url('/'));

        if(!isset($parts['host'])) $lang = 'en';
        else {
            $host = $parts['host'];
            $host = str_replace('www.', '', $host);

            $domain = Domain::where('domain', $host)->first();

            if($domain)
            {
                $lang = $domain->lang;
            }
            else $lang = 'en';
        }

        Config::set('lang', $lang);

        return $next($request);
    }
}
