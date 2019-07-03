<?php

namespace App\Http\Middleware;

use Closure;
use Helpers\GeneralHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

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
        $ip = GeneralHelper::visitorIpCloudFlare();

        View::share('testMode', GeneralHelper::isTestMode());

        $partnerPage = config('app.foreignPages.partner');
        View::share('partnerPage', $partnerPage);
        View::share(['currentUser' => Auth::check() ? Auth::user() : false]);

        return $next($request);
    }
}
