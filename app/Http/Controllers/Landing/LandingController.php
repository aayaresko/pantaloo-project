<?php

namespace App\Http\Controllers\Landing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function main(Request $request)
    {
        $entryPoint = config('app.foreignPages.main');
        return redirect($entryPoint);
    }

    /**
     * @param Request $request
     * @param string $lang
     * @return mixed
     */
    public function generalLending(Request $request, $lang = 'en')
    {
        $fullUrl = $request->fullUrl();
        $parsedUrl = parse_url($fullUrl);
        $getQuery = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

        $getParameters = '/?' . $getQuery;
        $mainUrl = config('app.foreignPages.main');
        $defaultView = 'landingPages.general';
        //to do this - 
        if ($lang !== 'en') {
            $currentView = $defaultView . '_' . $lang;
        } else {
            $currentView = $defaultView;
        }
        
        return view($currentView)->with([
            'lang' => $lang,
            'mainUrl' => $mainUrl,
            'getParameters' => $getParameters,
        ]);
    }
}
