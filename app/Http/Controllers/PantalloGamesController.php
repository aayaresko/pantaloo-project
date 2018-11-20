<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use  App\Modules\PantalloGames;

class PantalloGamesController extends Controller
{

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

    public function createPlayer(Request $request)
    {

        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->createPlayer();
    }

    public function playerExists(Request $request)
    {
        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->getGameList();
    }

    public function loginPlayer(Request $request)
    {
        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->getGameList();
    }

    public function getGame(Request $request)
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

    public function getGameDirect(Request $request)
    {
        //what is game
        $this->validate($request, [
            'gameID' => 'required|numeric|min:1',
        ]);
        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->getGameList();
    }

    public function logoutPlayer(Request $request)
    {
        $pantalloGames = new PantalloGames;
        $params = [];
        return $pantalloGames->getGameList();
    }
}
