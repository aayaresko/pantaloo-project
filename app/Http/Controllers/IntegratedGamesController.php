<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\RawLog;
use App\User;
use App\Slots\Casino;
use App\Transaction;
use App\Http\Requests;
use App\Models\GamesList;
use App\Models\GamesType;
use App\Models\GamesCategory;
use Illuminate\Http\Request;

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

        if ($request->has('search')) {
            array_push($whereGameList, ['name', 'LIKE', '%' . $request->search . '%']);
        }

        if ($request->has('category_id')) {
            array_push($whereGameList, ['category_id', '=', $request->category_id]);
        }

        if ($request->has('type_id')) {
            array_push($whereGameList, ['type_id', '=', $request->type_id]);
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
        $a = GamesList::paginate(20);
        dd($a);
        return view('integrated_games');
    }
}
