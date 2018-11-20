<?php

namespace App\Http\Controllers;

use  App\Modules\PantalloGames;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {

        $pantalloGames = new PantalloGames;
        $getGameList = $pantalloGames->getGameList();
        dd($getGameList);



        $post = [
            'api_login' => 'casinobit_mc_s',
            'api_password' => 'SPHhcXLHSZyg28OlpY',
            'method' => 'getGameList',
            'show_systems' => 0,
            'currency' => 'EUR',
        ];
        $ch = curl_init('https://stage.game-program.com/api/seamless/provider');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        var_dump($response);
        dd(1);

        $client = new Client(['verify' => false]);

        $result = $client->post('https://stage.game-program.com/api/seamless/provider', [
            'form_params' => [
                'api_password' => 'casinobit_mc_s',
                'api_login' => 'SPHhcXLHSZyg28OlpY',
                'method' => 'getGameList',
                'show_systems' => 0,
                'currency' => 'EUR',
            ]
        ]);
        dd($result->getBody());
       return view('testtest');
    }
}
