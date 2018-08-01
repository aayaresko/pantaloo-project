<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class EmailConfirmation
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
        if(Auth::check())
        {
            $routes = collect([
                'home',
                'main',
                'email.confirm',
                'logout',
                'email.activate'
            ]);
            
            $route_name = Request::route()->getName();

            if(!$routes->contains($route_name)) {

                if($request->path() == 'logout') return $next($request);

                if (Auth::user()->confirmation_required == 1 and Auth::user()->email_confirmed == 0) {
                    if (Auth::user()->transactions()->deposits()->count() == 0) {
                        return redirect('/')->with('popup_fixed', 'true');
                    }
                }
            }
        }

        return $next($request);
    }
}
