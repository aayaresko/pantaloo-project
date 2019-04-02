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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generalLending(Request $request)
    {
        $fullUrl = $request->fullUrl();
        $parsedUrl = parse_url($fullUrl);

        $getParameters = $parsedUrl['query'];
        $mainUrl = config('app.foreignPages.main');
        return view('landingPages.general')->with([
            'mainUrl' => $mainUrl,
            'getParameters' => $getParameters,
        ]);
    }
}
