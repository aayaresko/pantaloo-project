<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\RawLog;
use App\User;
use App\Transaction;
use App\Http\Requests;
use App\Models\GamesList;
use Illuminate\Http\Request;

class IntegratedGamesController extends Controller
{

    public function __construct()
    {

    }

    public function getGames(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $gamesProviders = $configIntegratedGames['provider'];
        $start = microtime(true);

        $countProviders = count($gamesProviders);
        $whereCommon = [
            ['id', '>', 1],
            ['name', 'like', '%Jack%'],
        ];
        $selectCommon = ['id', 'name'];
        $games = null;
        for ($i = 1; $i <= $countProviders; $i++) {
            if ($i === 1) {
                $games = $gamesProviders[$i - 1]::select($selectCommon)->where($whereCommon);
            } else {
                ${'provider' . $i} = $gamesProviders[$i - 1]::select($selectCommon)->where($whereCommon);
                $games = $games->unionAll(${'provider' . $i});
            }
            if ($i === $countProviders) {
                $games = $games->orderBy('id', 'desc')->skip(0)->take(40)->get()->toArray();
            }
        }
        dump('Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.');
        dd($games);

        //$gamesProvider  = config()
        $a = GamesList::paginate(20);
        $b = GamesList::paginate(20);
        dd($a);
        return view('integrated_games');
    }

    public function getGame(Request $request)
    {
        $a = GamesList::paginate(20);
        dd($a);
        return view('integrated_games');
    }
}
