<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use Validator;
use App\Http\Controllers\Controller;
use App\Slots\Casino;
use App\Models\GamesList;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesCategory;


class IntegratedGamesController extends Controller
{

    public function index(Request $request)
    {
        $gameList = GamesList::all();
        return view('admin.integrated_games')->with(['gameList' => $gameList]);
    }
}
