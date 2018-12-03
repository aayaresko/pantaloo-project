<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\PantalloGames;
use App\Modules\Games\PantalloGamesSystem;

/**
 * Class PantalloGamesController
 * @package App\Http\Controllers
 */
class PantalloGamesController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function endpoint(Request $request)
    {
        $pantalloGamesSystem = new PantalloGamesSystem();
        $response = $pantalloGamesSystem->callback($request);
        return response()->json($response);
    }


    /**
     * @param Request $request
     * @return int
     */
    public function getGameList(Request $request)
    {
        $pantalloGames = new PantalloGames;
        $params = [];
        $games = $pantalloGames->getGameList($params, true);
        return $games->response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginPlayer(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'gameId' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $pantalloGamesSystem = new PantalloGamesSystem();
        $response = $pantalloGamesSystem->loginPlayer($request);
        return response()->json($response);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutPlayer(Request $request)
    {
        $pantalloGamesSystem = new PantalloGamesSystem();
        $response = $pantalloGamesSystem->logoutPlayer($request);
        return response()->json($response);
    }
}