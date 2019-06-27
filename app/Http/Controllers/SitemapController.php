<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

class SitemapController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getSiteMap(Request $request)
    {
        $name = 'sitemap.xml';
        $path = storage_path("app/public") . '/' . $name;
        if (!file_exists($path)) {
            Artisan::call('createSitemap');
        }
        return response()->file($path);
    }

    public function index()
    {
        return response(file_get_contents(public_path('sitemap.xml')), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
