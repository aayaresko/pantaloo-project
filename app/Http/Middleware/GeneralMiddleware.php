<?php

namespace App\Http\Middleware;

use App\Providers\DataLayerProvider;
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

        //for ga
        $backEventTypes = config('appAdditional.backEventType');
        View::share('backEventTypes', $backEventTypes);
        //for ga

        if (Auth::check()){
            $user = Auth::user();
            \DataLayerHelper::set('userId', $user->id);
        }
        \DataLayerHelper::set('userIP', GeneralHelper::visitorIpCloudFlare());

        //set current value for lang code
        $currentLangCodes = config('translator.currentLangCode');
        View::share('currentLangCodes', $currentLangCodes);
        //set current value for lang code

        return $next($request);
    }
}
