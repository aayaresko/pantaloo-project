<?php

namespace App\Http\Middleware;

use Closure;
use App\Domain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use App\Http\Requests\Request;

class LanguageSet
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
        $parts = parse_url($request->fullUrl());

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

        $domain = Domain::where('lang', '<>', $lang)->first();

        $change_url = 'https://' . $domain->domain;

        if(isset($parts['path'])) $change_url = $change_url . $parts['path'];

        if(Auth::check()) {
            $change_url = $change_url . '?session_id=' . encrypt(json_encode(['id' => Auth::user()->id, 'password' => Auth::user()->password, 'email' => Auth::user()->email]));
        }

        View::share('change_url', $change_url);
        View::share('change_lang', $domain->lang);

        return $next($request);
    }
}
