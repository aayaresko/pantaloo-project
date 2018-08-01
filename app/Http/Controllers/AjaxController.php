<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function balance()
    {
        return response()->json(['balance' => Auth::user()->getBalance()]);
    }
}
