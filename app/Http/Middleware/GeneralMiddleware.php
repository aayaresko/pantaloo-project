<?php

namespace App\Http\Middleware;

use Closure;
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
        $partnerPage = config('app.foreignPages.partner');
        View::share('partnerPage', $partnerPage);
        return $next($request);
    }
}
