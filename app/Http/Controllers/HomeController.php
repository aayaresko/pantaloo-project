<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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

    public function index()
    {
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

        $url = rtrim(url("/$lang", [], GeneralHelper::isSecureProtocol()) . $_SERVER['REQUEST_URI'], '/');
        return redirect($url, 301);
    }
}
