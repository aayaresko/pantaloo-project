<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class GeneralMiddleware
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
        if (!$request->cookies->has('betatest')){
            return redirect('/coming_soon');
        }

        $partnerPage = config('app.foreignPages.partner');
        View::share('partnerPage', $partnerPage);
        return $next($request);
    }
}
