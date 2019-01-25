<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Validator;
use Helpers\GeneralHelper;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesCategory;
use App\Modules\PantalloGames;
use GuzzleHttp\Client;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloSessionGame;
use Illuminate\Http\Request;
use App\Models\GamesTypeGame;

class TestController extends Controller
{
    const PASSWORD = 'rf3js1Q';

    public function test(Request $request)
    {
        dd(2222);
        $pantalloGames = new PantalloGames;
        $allGames = $pantalloGames->getGameList([], true);
        $cat = [];
        $subcat = [];
        foreach ($allGames->response as $item) {
            dd($item);
            $subcat[$item->subcategory] = $item->subcategory;
            $cat[$item->category] = $item->category;
        }
        dump($cat);
        dd($subcat);
        GamesList::where('id', 1)
            ->update([
                'name' => 'Zdffd',
                'updated_at' => DB::raw('updated_at')]);
        dd(2);
        return view('emails.confirm')->with(['link' => 'https://www.casinobit.io/activate/be532c9328437e9a9a24b83bf70b349f4914217a2ae8e8fe9822d017000f77d4']);
        dd($current_user = trim(shell_exec('whoami')));
        GamesTypeGame::where([
            'type_id' => 10002,
            'extra' => 1,
        ])->delete();

        dd(55);
        $gameList = DB::table('games_types_games')->select(['games_list.id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where([
                ['games_types_games.extra', '=', 1],
                ['games_types_games.type_id', '=', 1]
            ])
            ->groupBy('games_types_games.game_id')->get();
        //dd($gameList);
        foreach ($gameList as $game) {
            GamesTypeGame::create([
                'game_id' => $game->id,
                'type_id' => 10001,
                'extra' => 1,
            ]);
        }


        dd(2345354356436);
        $aa = [
            300
            , 639
            , 640
            , 642
            , 643
            , 644
            , 645
            , 646
            , 647
            , 648
            , 653
            , 654
            , 655
            , 656
            , 657
            , 658
            , 659
            , 660
            , 661
            , 662
            , 663
            , 664
            , 665
            , 666
            , 667
            , 668
            , 669
            , 670
            , 671
            , 672
            , 673
            , 674
            , 680
            , 681
            , 682
            , 683
            , 686
            , 687
            , 688
            , 689
            , 690
            , 693
            , 696
            , 695
            , 694
            , 697
            , 698
            , 699
            , 700
            , 701
            , 942
            , 943
            , 944
            , 945
            , 946
            , 947
            , 948
            , 949
            , 950
            , 951
            , 952
            , 953
            , 954
            , 955
            , 956
            , 957
            , 958
            , 959
            , 960
            , 961
            , 962
            , 963
            , 964
            , 965
            , 966
            , 967
            , 968
            , 969
            , 970
            , 971
            , 972
            , 973
            , 974
            , 975
            , 976
            , 977
            , 978
            , 979
            , 980
            , 981
            , 982
            , 983
        ];

        foreach ($aa as $id) {
            GamesTypeGame::where([
                'game_id' => $id,
                'type_id' => 10003,
                'extra' => 1,
            ])->delete();
        }

        dd(111);
        $gameList = DB::table('games_types_games')->select(['games_list.id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where([
                ['games_types_games.extra', '=', 1],
                ['games_types_games.type_id', '=', 5]
            ])
            ->whereNotIn('games_list.id', [

            ])
            ->groupBy('games_types_games.game_id')->get();
        dd($gameList);
        foreach ($gameList as $game) {
            GamesTypeGame::create([
                'game_id' => $game->id,
                'type_id' => 10002,
                'extra' => 1,
            ]);
        }


        dd(2222234455555566666);
        $a = file_get_contents('https://www.casinobit.ioa/');
        dd($a);
        dd(GeneralHelper::fullRequest());
        $url = 'https://www.casinobit.io/games/endpoint?callerId=casinobit_mc_s&callerPassword=302e6543f24cfabc19a360deaa09096b8733f780&callerPrefix=z1am&action=debit&remote_id=969111&username=136&session_id=5bfc06cfd06f7&currency=USD&amount=0.15&provider=gs&game_id=2058&game_id_hash=gs_gs-african-sunset&transaction_id=gs-1954554737-545af1&round_id=-2055295972&gameplay_final=0&is_freeround_bet=0&jackpot_contribution_in_amount=0&gamesession_id=gs_a027a-79972660&key=d65da999c6e8b20337ee5ddf8311a1eb70c4a8a7';
        //$url = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json';
        $clientParams = ['verify' => false];
        $client = new Client($clientParams);
        for ($i = 10; $i < 20; $i++) {
            $response = $client->get("https://www.casinobit.io/games/endpoint?callerId=casinobit_mc_s&callerPassword=302e6543f24cfabc19a360deaa09096b8733f780&callerPrefix=z1am&action=debit&remote_id=969111&username=136&session_id=5bfc06cfd06f7&currency=USD&amount=1&provider=gs&game_id=2058&game_id_hash=gs_gs-african-sunset&transaction_id=gs-1954554737-545af1$i&round_id=-2055295972&gameplay_final=0&is_freeround_bet=0&jackpot_contribution_in_amount=0&gamesession_id=gs_a027a-79972660&key=d65da999c6e8b20337ee5ddf8311a1eb70c4a8a7");
        }
        dd('Ok');
        $response = $client->get($url);
        dd($response->getBody()->getContents());
        dd($response);
        $am = 1000;
        User::where('id', 1)
            ->update([
                'balance' => DB::raw("balance+$am")
            ]);
        dd(2);

        $test = User::where('id', 1)->update([
            'balance' => DB::raw("balance+$am")
        ]);
        dd(2);

        DB::enableQueryLog();
        $data = User::updateOrCreate(['id' => 1], ['balance' => 212]);
        dump($data->toArray());
        dd(DB::getQueryLog());
//        $update = User::where(
//            [
//                ['id', '=', 1],
//                [DB::raw("balance"), '>',  1000]
//            ]
//        )->first();
//        dd($update->toArray());
//        $amount = 20;
        $test = User::where('id', 1)->update([
            'balance' => -1
        ]);
        dd($test);
        dd($request->fullUrl());
        $validator = Validator::make($request->all(), [
            'title' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            dd($error->first());
            throw new \Exception('Insufficient funds', 500);
        }


        $userFields = ['users.id as id', 'users.balance as balance', 'affiliates.id as partner_id', 'affiliates.commission as partner_commission'];
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
