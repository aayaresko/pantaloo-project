<?php

namespace App\Http\Middleware;

use Closure;
use Helpers\GeneralHelper;
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
        $ip = GeneralHelper::visitorIpCloudFire();
        //dd($ip);


        //dump($request);

        //if (!$request->cookies->has('betatest') && !in_array($ip, ['172.68.110.111', '46.28.207.238']) && !in_array($request->getRequestUri(), [''])){
            //return redirect('/coming_soon');
        //}


        $partnerPage = config('app.foreignPages.partner');
        View::share('partnerPage', $partnerPage);
        return $next($request);
    }
}
