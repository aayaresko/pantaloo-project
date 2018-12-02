<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\Slots\Casino;
use Illuminate\Http\Request;
use App\Models\GamesList;
use App\Models\GamesType;
use App\Models\GamesCategory;

class IntegratedGamesController extends Controller
{

    public function index(Request $request)
    {
        $gamesTypes = GamesType::where([
            ['active', '=', 1],
        ])->orderBy('rating', 'desc')->get();

        $gamesCategories = GamesCategory::where([
            ['active', '=', 1],
        ])->orderBy('rating', 'desc')->get();

        return view('integrated_games')->with([
            'gamesTypes' => $gamesTypes,
            'gamesCategories' => $gamesCategories
        ]);
    }

    public function getGames(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        //check this i use alien code
        if (Casino::isMobile()) {
            $paginationCount = $configIntegratedGames['listGames']['pagination']['mobile'];
        } else {
            $paginationCount = $configIntegratedGames['listGames']['pagination']['desktop'];
        }

        $whereGameList = [
            ['active', '=', 1],
        ];

        if ($request->search !== '') {
            array_push($whereGameList, ['name', 'LIKE', '%' . $request->search . '%']);
        }

        if ((int)$request->categoryId !== 0) {
            array_push($whereGameList, ['category_id', '=', $request->categoryId]);
        }

        if ((int)$request->typeId !== 0) {
            array_push($whereGameList, ['type_id', '=', $request->typeId]);
        }

        $gameList = GamesList::where($whereGameList)->orderBy('rating')->paginate($paginationCount);

        $viewMobile = (string)view('load.integrated_games_list_mobile')->with(['gameList' => $gameList]);
        $viewDesktop = (string)view('load.integrated_games_list_desktop')->with(['gameList' => $gameList]);

        return response()->json([
            'mobile' => $viewMobile,
            'desktop' => $viewDesktop
        ]);
    }

    public function getGame(Request $request)
    {
        //use provider and class get games
        return view('integrated_games');
    }
}
