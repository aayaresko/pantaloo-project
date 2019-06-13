<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }
}
