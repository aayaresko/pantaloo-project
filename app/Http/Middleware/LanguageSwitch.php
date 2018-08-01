<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
class LanguageSwitch
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
        if($request->has('session_id'))
        {
            $data = json_decode(decrypt($request->input('session_id')), true);

            $user = User::where('id', $data['id'])->where('email', $data['email'])->where('password', $data['password'])->first();

            if($user)
            {
                Auth::login($user);
            }

            return redirect(url($request->path()));
        }

        return $next($request);
    }
}
