<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesCategory;
use App\Modules\PantalloGames;
use GuzzleHttp\Client;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloSessionGame;
use Illuminate\Http\Request;

class TestController extends Controller
{
    const PASSWORD = 'rf3js1Q';

    public function test(Request $request)
    {
        $userFields = ['users.id as id',  'users.balance as balance', 'affiliates.id as partner_id', 'affiliates.commission as partner_commission'];
        $user = User::select($userFields)->leftJoin('users as affiliates', 'users.agent_id', '=', 'affiliates.id')->where('users.id', 136)->first();
        dd($user->toArray());
        //->leftJoin('posts', 'users.id', '=', 'posts.user_id')
        $types = GamesType::all();
        return view('test.listTypes')->with(['types' => $types]);
//
//        dd(11);
//        $GamesCategory = GamesCategory::all()->keyBy('code');
//        dd($GamesCategory['fugaso']);
//        $allGames = file_get_contents(base_path().'/gameList.txt');
//        dd(json_decode($allGames));
//        dd($request->user());
        ini_set('max_execution_time', 60);
        $pantalloGames = new PantalloGames;
        $getGameList = $pantalloGames->getGameList([], true);
        dd(count($getGameList->response));


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

    public function testTypes(Request $request)
    {
        $games = GamesList::leftJoin('games_types', 'games_types.id', '=', 'games_list.type_id')
            ->where([
                ['games_types.code', '=', $request->category]
            ])->select(['games_types.id', 'games_list.name'])->get();
        return view('test.listGames')->with(['games' => $games]);
    }

    public function game(Request $request)
    {
        try {
            $game = GamesList::where('id', $request->game)->first();
            dump($game);
            $gameId = $game->system_id;
            $user = $request->user();
            $userId = $user->id;
            $pantalloGames = new PantalloGames;
            $playerExists = $pantalloGames->playerExists([
                'user_username' => $user->id,
            ], true);

            //active player request
            if ($playerExists->response === false) {
                $player = $pantalloGames->createPlayer([
                    'user_id' => $userId,
                    'user_username' => $userId,
                    'password' => self::PASSWORD
                ], true);
            } else {
                $player = $playerExists;
            }

            //login request
            $login = $pantalloGames->loginPlayer([
                'user_id' => $userId,
                'user_username' => $userId,
                'password' => self::PASSWORD
            ], true);

            $loginResponse = (array)$login->response;
            $idLogin = $loginResponse['id'];
            unset($loginResponse['id']);
            $loginResponse['system_id'] = $idLogin;
            $loginResponse['user_id'] = $userId;
            GamesPantalloSession::updateOrCreate(['sessionid' => $loginResponse['sessionid']], $loginResponse);
            dump($gameId);
            //get games
            $getGame = $pantalloGames->getGame([
                'lang' => 'en',
                'user_id' => $user->id,
                'user_username' => $user->id,
                'user_password' => self::PASSWORD,
                'gameid' => $gameId,
                'play_for_fun' => 0,
                'homeurl' => url(''),
            ], true);
            dump($idLogin);
            dump($getGame);
            GamesPantalloSessionGame::create(['session_id' => $idLogin,
                    'gamesession_id' => $getGame->gamesession_id]);

            return view('testtest', ['link' => $getGame]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
