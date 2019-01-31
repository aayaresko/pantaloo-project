<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\GamesList;
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
        $user = $request->user();
        $response = $pantalloGamesSystem->logoutPlayer($user);
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function freeRound(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'gameId' => 'numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        $configFreeRounds = config('appAdditional.freeRounds');
        if ($request->has('gameId')) {
            $gamesIds = $request->gameId;
        } else {
            $freeRoundGames = GamesList::select(['id', 'system_id'])->where('free_round', 1)->get()->toArray();
            $gamesIds = implode(',', array_map(function ($item) {
                return $item['system_id'];
            }, $freeRoundGames));
        }

        $request->merge(['gamesIds' => $gamesIds]);
        $request->merge(['available' => $configFreeRounds['available']]);
        $request->merge(['timeFreeRound' => $configFreeRounds['timeFreeRound']]);

        $pantalloGamesSystem = new PantalloGamesSystem();

        $response = $pantalloGamesSystem->freeRound($request);
        return response()->json($response);
    }
}