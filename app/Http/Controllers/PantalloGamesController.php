<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use  App\Modules\PantalloGames;

class PantalloGamesController extends Controller
{
    //why constant - in doc for integration write such make
    const PASSWORD = 'r';

    public function endpoint(Request $request){

        return response()->json([
            'status' => 200,
            'balance' => 0
        ]);
    }

    public function getGameList(Request $request)
    {
        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->getGameList($params);
    }

    public function loginPlayer(Request $request)
    {

//        try{
//
//        } catch (\Exception $e){
//
//        }


        $user = $request->user();
        $pantalloGames = new PantalloGames;
        $playerExists = $pantalloGames->playerExists([
            'user_username' => $user->id,
        ], true);

        if ($playerExists->response === false ) {
            $player = $pantalloGames->createPlayer([
                'user_id' => $user->id,
                'user_username' => $user->id,
                'password' => self::PASSWORD
            ], true);
        } else {
            $player = $playerExists;
        }

        //login
        $login = $pantalloGames->loginPlayer([
            'user_id' => $user->id,
            'user_username' => $user->id,
            'password' => self::PASSWORD
        ]);


        dd($login);
        dd($pantalloGames->createPlayer([
            'user_id' => $user->id,
            'user_username' => $user->id,
            'password' => self::PASSWORD
        ]));
        dd($pantalloGames->playerExists());
        $params = [];
        return $pantalloGames->getGameList();
    }

    public function logoutPlayer(Request $request)
    {
        //what is game
        //what is game
        $this->validate($request, [
            'gameID' => 'required|numeric|min:1',
        ]);
        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->getGameList();
    }
}
