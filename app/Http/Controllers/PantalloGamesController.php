<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\GamesPantalloSession;
use App\Modules\PantalloGames;

class PantalloGamesController extends Controller
{
    //why constant - in doc for integration write such make
    const PASSWORD = 'rf3js1Q';

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
        $this->validate($request, [
            'gameId' => 'required|numeric|min:1',
        ]);
        try {
            $gameId = $request->gameId;
            $user = $request->user();
            $pantalloGames = new PantalloGames;
            $playerExists = $pantalloGames->playerExists([
                'user_username' => $user->id,
            ], true);

            //active player request
            if ($playerExists->response === false ) {
                $player = $pantalloGames->createPlayer([
                    'user_id' => $user->id,
                    'user_username' => $user->id,
                    'password' => self::PASSWORD
                ], true);
            } else {
                $player = $playerExists;
            }

            //login request
            $login = $pantalloGames->loginPlayer([
                'user_id' => $user->id,
                'user_username' => $user->id,
                'password' => self::PASSWORD
            ], true);

            $loginResponse = $login->response;
            $idLogin = $loginResponse->id;
            unset($loginResponse['id']);
            $loginResponse['user_id'] = $idLogin;
            dd($loginResponse);
            GamesPantalloSession::create($loginResponse);

            //get games
            $getGame = $pantalloGames->getGame([
                'lang' => 'en',
                'user_id' => $user->id,
                'user_username' => $user->id,
                'user_password' => self::PASSWORD,
                'gameId' => $gameId,
                'play_for_fun' => 0,
                'homeurl' => url(),
            ], true);

            return response()->json([
                'success' => false,
                'message' => [
                    'gameLink' => $getGame->response
                ]
            ]);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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
