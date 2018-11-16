<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class GamesController extends Controller
{

    public function endpoint(Request $request){

        return response()->json([
            'status' => 200,
            'balance' => 0
        ]);
    }

    public function balance(Request $request){

        return response()->json([
            'status' => 200,
            'balance' => 0
        ]);
    }

    public function debit(Request $request){

        return response()->json([
            'status' => 200,
            'balance' => 0
        ]);
    }

    public function credit(Request $request){

        return response()->json([
            'status' => 200,
            'balance' => 0
        ]);
    }

    public function rollback(Request $request){

        return response()->json([
            'status' => 200,
            'balance' => 0
        ]);
    }
}
