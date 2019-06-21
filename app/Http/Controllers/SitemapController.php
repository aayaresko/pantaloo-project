<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    //
    public function index(){
        return response(file_get_contents(public_path('sitemap.xml')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
