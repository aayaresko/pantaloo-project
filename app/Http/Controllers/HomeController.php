<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Torann\LaravelMetaTags\Facades\MetaTag;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('home');
    }

    public function index(Request $request, $lang = '')
    {
        if ('en' == $lang){
            return redirect('/');
        }

        MetaTag::set('title', trans("metatag.main__title"));
        MetaTag::set('description', trans("metatag.main__description"));

        return view('home');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function multiLang(Request $request)
    {
        //find to lang
        $sessionFlashAll = session('flash');
        $sessionFlash = $sessionFlashAll['old'];
        $lang = config('currentLang');

        //dd($lang);
        if ('en' != $lang) {
            $url = rtrim(url("/$lang", [], GeneralHelper::isSecureProtocol()) . $_SERVER['REQUEST_URI'], '/');
            return redirect($url, 301);
        }

        return $this->index($request);

    }
}
