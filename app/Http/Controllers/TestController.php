<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $client = new Client();
        $result = $client->post('https://stage.game-program.com/api/seamless/provider', [
            'form_params' => [
                'api_password' => 'casinobit_mc_s',
                'api_login' => 'SPHhcXLHSZyg28OlpY',
                'method' => 'getGameList',
                'show_systems' => 0,
                'currency' => 'EUR',
            ]
        ]);
        dd($result);
       return view('testtest');
    }
}
