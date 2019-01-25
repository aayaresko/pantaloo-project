<?php

namespace App\Http\Middleware;

use App;
use Cookie;
use Closure;
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
        '/games/pantallo/endpoint'
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
        $lang = Cookie::get('lang');

        //pass variable
        View::share('languages', $languages);
        View::share('currentLang', $lang);

        if (!is_null($lang)) {
            App::setlocale($lang);
        } else {
            //check ip address and check language
            //if difference then ask message with gow language select
            //and set language above
            //App::setlocale($lang);
            //if we don't have this language we use en
        }


        if ($request->has('session_id')) {
            $data = json_decode(decrypt($request->input('session_id')), true);

            $user = User::where('id', $data['id'])
                ->where('email', $data['email'])->where('password', $data['password'])->first();

            if ($user) {
                Auth::login($user);
            }

            return redirect(url($request->path()));
        }

        return $next($request);
    }
}
