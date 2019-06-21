<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function balance()
    {
        return response()->json(['balance' => Auth::user()->getBalance()]);
    }
}
