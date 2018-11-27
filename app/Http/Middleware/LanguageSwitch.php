<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LanguageSwitch extends CommonMiddleware
{
    /**
     * @var array
     */
    protected $except = [
        '/games/endpoint'
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $shouldPassThrough = $this->shouldPassThrough($request);
        if ($shouldPassThrough) {
            return $next($request);
        }

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
