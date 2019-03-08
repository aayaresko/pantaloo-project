<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
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
    public function multiLang()
    {
        //find to lang
        $sessionFlashAll = session('flash');
        $sessionFlash = $sessionFlashAll['old'];
        $lang = config('currentLang');
        //save parameters
        $url = url("/$lang") . $_SERVER['REQUEST_URI'];
        if (!empty($sessionFlash)) {
            if (!in_array('errors', $sessionFlash)) {
                $sessionFlashKey = $sessionFlash[0];
                $sessionFlashArray = session($sessionFlashKey);
                return redirect($url)->with($sessionFlashKey, $sessionFlashArray);
            } else {
                $sessionFlashArray = session('errors')->all();
                return redirect($url)->withErrors($sessionFlashArray);
            }
        } else {
            return redirect($url);
        }
    }
}
