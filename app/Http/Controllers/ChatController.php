<?php


namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use Torann\LaravelMetaTags\Facades\MetaTag;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function privacyPolicy()
    {
        MetaTag::set('title', trans("metatag.privacy-policy__title"));
        MetaTag::set('description', trans("metatag.privacy-policy__description"));
        return view('privacy-policy');
    }
}
