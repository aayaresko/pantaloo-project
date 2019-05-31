<?php

namespace App\Http\Middleware;

use App;
use Config;
use Cookie;
use Closure;
use Request;
use App\User;
use Helpers\GeneralHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class LanguageSwitch extends CommonMiddleware
{
    /**
     * @var array
     */
    protected $except = [
        '/games/endpoint',
        '/games/pantallo/endpoint',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $shouldPassThrough = $this->shouldPassThrough($request);
        if ($shouldPassThrough) {
            return $next($request);
        }

        $languages = GeneralHelper::getListLanguage();
        $timeKeepLang = config('appAdditional.keepLanguage');
        $prefixLang = $request->route()->parameter('lang');
        $cookieLang = Cookie::get('lang');

        if (! is_null($prefixLang)) {
            $lang = $prefixLang;
        } else {
            if (! is_null($cookieLang)) {
                $lang = $cookieLang;
            } else {
                $lang = app()->getLocale();
                //check ip address and check language
                //if difference then ask message with gow language select
                //and set language above
                //App::setlocale($lang);
                //if we don't have this language we use en
            }
        }

        App::setlocale($lang);

        //pass variable
        View::share('languages', $languages);
        View::share('currentLang', $lang);
        Config::set('currentLang', $lang);
        Cookie::queue('lang', $lang, $timeKeepLang);

        /*
        if ($request->has('session_id')) {
            $data = json_decode(decrypt($request->input('session_id')), true);

            $user = User::where('id', $data['id'])
                ->where('email', $data['email'])->where('password', $data['password'])->first();

            if ($user) {
                Auth::login($user);
            }

            return redirect(url($request->path()));
        }
        */

        return $next($request);
    }
}
