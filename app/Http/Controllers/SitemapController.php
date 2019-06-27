<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

class SitemapController extends Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        return \SitemapHelper::build();
    }
}
