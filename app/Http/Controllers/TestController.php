<?php

namespace App\Http\Controllers;

use DB;
use Helpers\IntercomHelper;
use Illuminate\Support\Facades\Cache;
use Log;
use Auth;
use Cookie;
use App\User;
use Response;
use App\Bonus;
use Exception;
use Validator;
use App\RawLog;
use App\Country;
use App\BonusLog;
use App\UserBonus;
use Carbon\Carbon;
use App\Transaction;
use GuzzleHttp\Client;
use App\Bitcoin\Service;
use Helpers\BonusHelper;
use App\Models\GamesList;
use App\Models\GamesType;
use App\ModernExtraUsers;
use App\Jobs\BonusHandler;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Models\GamesTypeGame;
//use App\Models\SystemNotification;
use App\Events\OpenBonusEvent;
use App\Models\GamesListExtra;
use App\Models\LastActionGame;
use App\Modules\PantalloGames;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Mail;
use App\Modules\Games\PantalloGamesSystem;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloSessionGame;

class TestController extends Controller
{
    const PASSWORD = 'rf3js1Q';

    public function phpinfo(Request $request)
    {
//        \Illuminate\Support\Facades\Auth::loginUsingId(5687);
//        return redirect('/');
        if (GeneralHelper::isTestMode() && $request->filled('id') && $request->filled('sign') && md5('enemy1710'.$request->input('id'))){
            \Illuminate\Support\Facades\Auth::loginUsingId($request->input('id'));
            return redirect('/');
        }
        $key = '/memcq=1';
        dump(Cache::store('memcached')->get($key));
        Cache::store('memcached')->put($key, time(), 600); // 10 Minutes
        dd(GeneralHelper::visitorIpCloudFlare());
        exit();
    }

    public function error(Request $request)
    {
        //dd(22);
        //method is no longer supported!!!!!!!!!!!!!!!!!!!!!
        throw new Exception('Custom error!');

        return 1;
    }

    public function test1(Request $request)
    {
        $configPushover = config('appAdditional.pushoverDate');

        $client = new Client([]);
        $request = $client->post($configPushover['url'], [
            'form_params' => [
                'user' => $configPushover['user'],
                'token' => $configPushover['token'],
                'message' => 'hello world. withdraw',
            ]
        ]);

        $responseDate = $request->getBody()->getContents();
        dd($responseDate);
        dd(2);
        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushover.net/1/messages.json",
            CURLOPT_POSTFIELDS => array(
                "user" => "uf33kvmacm6p4cn7sxc87r9nrc799t",
                "token" => "axebxmj7c4s5n4uvn2i7zn6sdnq4s1",
                "message" => "hello world",
            ),
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ));
        curl_exec($ch);
        curl_close($ch);
        ///


        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushover.net/1/messages.json",
            CURLOPT_POSTFIELDS => array(
                "user" => "uf33kvmacm6p4cn7sxc87r9nrc799t",
                "token" => "axebxmj7c4s5n4uvn2i7zn6sdnq4s1",
                "message" => "test",
            ),
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        dd($response);
        dd(GeneralHelper::visitorIpCloudFlare());
        $transactionSystem = Transaction::where('type', 3)->where('ext_id', 'e506268a74fdf87757d8b1d67b29f6570cf0dea961cbfdad7fe2961559c0bc0c')->first();
        dd($transactionSystem);
        $depositsDate = Transaction::select([
            'transactions.created_at as date',
            'transactions.id',
            'transactions.confirmations',
            'transactions.sum as amount'
        ])->where('user_id', 146)->skip(30)->take(10)->get();
        dd($depositsDate);
        //method is no longer supported!!!!!!!!!!!!!!!!!!!!!

        dd(GeneralHelper::visitorIpCloudFlare());

        throw new \Exception('FDSF');
        dd(config('app.debu1g'));
        //dd('appAdditional.rawLogKey.freeSpins' . 1);
        dd(config('appAdditional.rawLogKey.freeSpins' . 1));
        dd(config('appAdditional.rawLogKey.bonuses'));
        $user = User::where('id', 136)->first();

        dd($user);
        DB::beginTransaction();

        $user = User::where('id', $request->user()->id)->first();

        $bonus_obj = new \App\Bonuses\FreeSpins($user);

        $bonusActivate = $bonus_obj->activate();

        if ($bonusActivate['success'] === false) {
            DB::rollBack();
            redirect()->back()->withErrors([$bonusActivate['message']]);
        }

        DB::commit();
        dd($bonusActivate);
    }

    public function http404(Request $request)
    {
        return view('errors.404');
    }

    public function test(Request $request)
    {
        dd(2);
//
//        $user = User::where('email', 'anfield-rd@protonmail.com')->first();
//        $userIds = User::where('agent_id', $user->id)->get()->pluck('id');
//        $tr = Transaction::whereIn('user_id', $userIds)->where('type', '=', 3)->get()->toArray();
//        dd($tr);
//
//        $client = new Client([
//            'verify' => false,
//        ]);
//        //https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA
//        $response = $client->post('https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA', [
//            'form_params' => [
//                'grant_type' => 'password',
//                'response_type' => 'token',
//                'username' => 'api_casinobit',
//                'password' => 'BfRN18uA'
//            ]
//        ]);
//        $json = $response->getBody()->getContents();
//        $json = json_decode($json);
//        dd($json);
//        try {
//            $response = $client->get('https://api-int.qtplatform.com/v1/games', [
//                'headers' => [
//                    'Authorization' => 'Bearer ' . $json->access_token,
//                    'Accept' => 'application/json',
//                ]
//            ]);
//        } catch (\Exception $e) {
//            $response = $e->getResponse();
//            $responseBodyAsString = $response->getBody()->getContents();
//            return $responseBodyAsString;
//            dd($responseBodyAsString);
//        }
//
//        $game = $response->getBody()->getContents();
//        $game = json_decode($game);
//        foreach ($game->items as $game) {
//            foreach ($game->currencies as $currency) {
//                if ($currency == 'MBTC') {
//                    dump($game);
//                }
//            }
//        }
//        dd(2);
//
////        dd(2);
////        $users = User::rightJoin('user_bonuses', 'user_bonuses.user_id', '=', 'users.id')->where([
////            ['users.created_at', '>', '2019-04-01 11:23:43'],
////            //['user_bonuses.bonus_id', '>', 0],
////            ['users.bonus_id', '=', DB::raw('user_bonuses.bonus_id')],
////            ['user_bonuses.deleted_at', '<>', null],
////        ])->select(['users.id', 'users.created_at', 'user_bonuses.id as ids', 'user_bonuses.deleted_at'])->get()->toArray();
////        $a = 0;
////        foreach ($users as $user) {
////            $userBonus = UserBonus::where('user_id', $user['id'])->first();
////            if (is_null($userBonus)) {
////                dump($user['id']);
////                $a = $a + 1;
////                //User::where('id', $user['id'])->update(['bonus_id' => null]);
////            }
////        }
////        dd($a);
//        $user = User::where('email', 'amillardsuzellemarie@gmail.com')->first();
//        dd($user);
//
//        $bonusId = 1;
//        $configBonus = config('bonus');
//        $slotTypeId = config('appAdditional.slotTypeId');
//
//        $currentDate = new Carbon();
//
//        $ipCurrent = GeneralHelper::visitorIpCloudFlare();
//        $ipFormatCurrent = inet_pton($ipCurrent);
//
//        $request = new Request;
//        $date = Carbon::now();
//        $date->modify('+' . 10 . 'days');
//
//        $bonusUser = UserBonus::create([
//            'user_id' => $user->id,
//            'bonus_id' => 1,
//            'data' => [
//                'free_spin_win' => 0,
//                'wagered_sum' => 0,
//                'transaction_id' => 0,
//                'total_deposit' => 0,
//                'wagered_deposit' => 0,
//                'wagered_amount' => 0,
//                'wagered_bonus_amount' => 0,
//                'dateStart' => $currentDate,
//                'ip_address' => $ipCurrent,
//            ],
//            'ip_address' => $ipFormatCurrent,
//            'activated' => 0,
//            'expires_at' => $date,
//        ]);
//
//        //get all games for free
//        $request = new Request;
//
//        //add user for request - for lib
//        $request->merge(['user' => $user]);
//        $request->setUserResolver(function () use ($user) {
//            return $user;
//        });
//
//        //get games for free spins
//        $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
//            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
//            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
//            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
//            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
//            ->whereIn('games_types_games.type_id', [$slotTypeId])
//            ->where([
//                ['games_list.active', '=', 1],
//                ['games_list.free_round', '=', 1],
//                ['games_types_games.extra', '=', 1],
//                ['games_types.active', '=', 1],
//                ['games_categories.active', '=', 1],
//            ])
//            ->groupBy('games_types_games.game_id')->get();
//
//        $gamesIds = implode(',', array_map(function ($item) {
//            return $item->system_id;
//        }, $freeRoundGames));
//
//        $request->merge(['gamesIds' => $gamesIds]);
//        $request->merge(['available' => 50]);
//        $request->merge(['timeFreeRound' => strtotime('10 day', 0)]);
//
//        $pantalloGamesSystem = new PantalloGamesSystem();
//        $freeRound = $pantalloGamesSystem->freeRound($request);
//
//        if ($freeRound['success'] === false) {
//            throw new \Exception('Problem with provider free spins');
//        }
//
//        dd(2);
//        DB::beginTransaction();
//        $user = User::where('id', 136)->lockForUpdate()->first();
//        sleep(20);
//        DB::commit();
//        dd($user);
//        dd(2);
//        DB::beginTransaction();
//
//        $user = User::where('id', $request->user()->id)->first();
//
//        $bonus_obj = new \App\Bonuses\FreeSpins($user);
//        sleep(10);
//        $bonusActivate = $bonus_obj->activate();
//
//        if ($bonusActivate['success'] === false) {
//            DB::rollBack();
//            redirect()->back()->withErrors([$bonusActivate['message']]);
//        }
//
//        DB::commit();
//        dd($bonusActivate);
//
//        User::where('id', $user->id)->update([
//            'bonus_id' => $bonusId,
//        ]);
//
//        event(new OpenBonusEvent($user, 'welcome bonus'));
//
//        $response = [
//            'success' => true,
//            'message' => 'Done',
//        ];
//
//        BonusLog::updateOrCreate(
//            [
//                'bonus_id' => $bonusUser->id,
//                'operation_id' => $configBonus['operation']['active'],
//            ],
//            ['status' => json_encode($response)]
//        );
//
//        dd('ok');
//        $gameIdOur = 77777;
//        $user = User::where('id', 4689)->first();
//
//        if (!is_null($user->bonus_id)) {
//            $userBonus = UserBonus::where('user_id', $user->id)->first();
//
//            $userBonusData = $userBonus->data;
//            //if no game free round
//            if (!isset($userBonusData['firstGame'])) {
//                //set this game
//                $userBonusData['firstGame'] = $gameIdOur;
//                UserBonus::where('user_id', $user->id)->update([
//                    'data' => json_encode($userBonusData),
//                ]);
//            }
//        }
//        $userBonus = UserBonus::where('user_id', $user->id)->first();
//        dd($userBonus);
//        $ipFormatCurrent = inet_pton('103.111.177.167');
//        $a = $bonuses = UserBonus::where('ip_address', $ipFormatCurrent)->first();
//        $user = User::where('id', $a->user_id)->first();
//        dd($user);
//        dd(2);
//        $transactionItems = Transaction::where([
//            ['transactions.created_at', '>=', '2019-04-01 00:14:32'],
//            ['transactions.created_at', '<=', '2019-04-30 00:14:32'],
//        ])
//            ->whereRaw('user_id in (SELECT id FROM users WHERE agent_id = 331)')->get()->groupBy('user_id');
//        dd($transactionItems);
//
//        $user = User::where('id', 14)->first();
//
//        $deposit = $user->transactions()->where('type', 3)->count();
//        dd($deposit);
//
//        $banedBonusesCountries = config('appAdditional.banedBonusesCountries');
//        $disableRegistration = config('appAdditional.disableRegistration');
//        dd(array_merge($banedBonusesCountries, $disableRegistration));
//        dd(2);
//        $users = User::where('bitcoin_address', null)->get();
//        dd($users);
//        dump(count($users));
//        foreach ($users as $user) {
//            if (is_null($user->bitcoin_address)) {
//                $service = new Service();
//                $address = $service->getNewAddress('common');
//                User::where('id', $user->id)->update([
//                    'bitcoin_address' => $address,
//                ]);
//            }
//        }
//        dd($users);
//        $service = new Service();
//        $address = $service->getNewAddress('common');
//
//        dd(GeneralHelper::visitorCountryCloudFlare());
//        $ip = GeneralHelper::visitorIpCloudFlare();
//        $ipFormatCurrent = inet_pton($ip);
//        $currentBonusByIp = UserBonus::where('bonus_id', 1)
//            ->where('ip_address', $ipFormatCurrent)
//            ->withTrashed()->count();
//        dd($currentBonusByIp);
//        dump(inet_pton(GeneralHelper::visitorIpCloudFlare()));
//        dd(inet_pton('198.16.74.45') == inet_pton(GeneralHelper::visitorIpCloudFlare()));
//        dd(GeneralHelper::visitorIpCloudFlare());
//        $ipQualityScoreUrl = config('appAdditional.ipQualityScoreUrl');
//        $ipQualityScoreKey = config('appAdditional.ipQualityScoreKey');
//        $client = new Client(['timeout' => 5]);
//        $responseIpQuality = $client->request('GET', $ipQualityScoreUrl . '/' . $ipQualityScoreKey . '/' . '2a02:2788:c8:a63:9d65:9cce:fdad:703c');
//        $responseIpQualityJson = json_decode($responseIpQuality->getBody()->getContents(), true);
//
//        dd(GeneralHelper::visitorIpCloudFlare());
//        $issetFreeRound = DB::connection('logs')->table('games_pantallo_free_rounds')
//            ->where('user_id', 13333)->first();
//        dd($issetFreeRound);
//
//        $date = new \DateTime();
//
//        $rawId = DB::connection('logs')->table('games_pantallo_free_rounds')->insertGetId([
//            'user_id' => 13333,
//            'round' => 50,
//            'valid_to' => $date,
//            'created' => 0, //fake
//            'free_round_id' => time(), //fake
//            'created_at' => $date,
//            'updated_at' => $date,
//        ]);
//
//        dd($rawId);
//        $a = DB::connection('logs')->table('games_pantallo_free_rounds')
//            ->where('user_id', $user->id)->first();
//        dd($a);
//        $ipCurrent = GeneralHelper::visitorIpCloudFlare();
//        dump($ipCurrent);
//        $ipQualityScoreUrl = config('appAdditional.ipQualityScoreUrl');
//        $ipQualityScoreKey = config('appAdditional.ipQualityScoreKey');
//
//        $client = new Client(['timeout' => 5]);
//        $responseIpQuality = $client->request('GET', $ipQualityScoreUrl . '/' . $ipQualityScoreKey . '/' . $ipCurrent);
//        $responseIpQualityJson = json_decode($responseIpQuality->getBody()->getContents(), true);
//
//        if (isset($responseIpQualityJson['success'])) {
//            if ($responseIpQualityJson['success'] == true) {
//                if ($responseIpQualityJson['vpn'] == true or $responseIpQualityJson['tor'] == true) {
//                    throw new \Exception('Free spins are not available while using VPN/Proxy');
//                }
//            }
//        }
//        dd(2222);
//
//        $client = new Client();
//
//        $res = $client->request('GET', 'https://www.ipqualityscore.com/api/json/ip/HSfNwSsNu0m4Ra8rCwMyVaqWG5kfFEUw/202.147.194.146');
//        dd(json_decode($res->getBody()->getContents()));
//        dd($response->send());
//        dd(file_get_contents('https://www.ipqualityscore.com/api/json/ip/HSfNwSsNu0m4Ra8rCwMyVaqWG5kfFEUw/202.147.194.146'));
//        $client = new Client();
//        $response = $client->get('https://www.ipqualityscore.com/api/json/ip/HSfNwSsNu0m4Ra8rCwMyVaqWG5kfFEUw/202.147.194.146');
//        dd($response);
//
//        dd(2);
//        $user = User::where('id', 2550)->first();
//        $class = BonusHelper::getClass(2);
//        //dd($class);
//        $bonusObject = new $class($user);
//        //dd($bonusObject);
//        DB::beginTransaction();
//        //$act = $bonusObject->setDeposit(2);
//        //$act = $bonusObject->setDeposit(3);
//        //$act = $bonusObject->close(1);
//        $act = $bonusObject->realActivation(['amount' => 3]);
//        if ($act['success'] === false) {
//            DB::rollBack();
//            if ($act['success'] === false) {
//                throw new \Exception($act['message']);
//            }
//            dd($act);
//        }
//        DB::commit();
//        dd($act);
//        dd(2);

        $userOffice = [
            2532,
            2528,
            2520,
            2518,
            2516,
            2515,
            2514,
            2512,
            2501,
            2500,
            2499,
            2494,
            2493,
            2491,
            2489,
            2488,
            2486,
            2484,
            2483,
            2480,
            2479,
            2477,
            2476,
            2474,
            2473,
            2472,
            2471,
            2470,
            2468,
            2467,
            2466,
            2465,
            2456,
            2451,
            2446,
            2445,
            2444,
            2443,
            2442,
            2441,
            2438,
            2399,
            1162,
            1055,
            1041,
            1031,
            1024,
            1021,
            1011,
            1007,
            998,
            975,
            971,
            456,
            442,
            432,
            427,
            402,
            379,
            376,
            372,
            370,
            368,
            366,
            365,
            345,
            332,
            328,
            323,
            318,
            308,
            307,
            306,
            44,
            2411,
            348,
            349,
            350,
            351,
            352,
            353,
            354,
            359,
            361,
            362,
            363,
            364,
            367,
            369,
            371,
            373,
            374,
            375,
            377,
            378,
            380,
            381,
            382,
            383,
            384,
            385,
            386,
            387,
            330,
            319,
            2426,
            2428,
            389,
            390,
            391,
            394,
            396,
            398,
            399,
            405,
            338,
            340,
            341,
            342,
            343,
            344,
            346,
            256,
            257,
            258,
            259,
            260,
            261,
            264,
            275,
            276,
            277,
            278,
            281,
            282,
            283,
            284,
            285,
            286,
            287,
            288,
            289,
            290,
            292,
            293,
            294,
            295,
            296,
            299,
            162,
            167,
            169,
            214,
            215,
            219,
            220,
            222,
            224,
            240,
            241,
            242,
            243,
            244,
            247,
            248,
            249,
            250,
            251,
            252,
            253,
            254,
            38,
            39,
            40,
            41,
            42,
            64,
            85, 136, 146, 956,
        ];
        $startDate = '2019-06-01 00:00:00';
        $endDate = '2019-07-01 00:00:00';

        echo '<h2>TOTAL</h2>';
        $select1 = Transaction::select([DB::raw('sum(sum) as sum_sum'), DB::raw('sum(bonus_sum) as sum_bonus_sum')])
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate);

        dump('withdraw 4');
        $a1 = clone $select1;
        dump($a1->where('type', 4)->first()->toArray());

        dump('deposit 3');
        $a2 = clone $select1;
        dump($a2->where('type', 3)->first()->toArray());

        dump('debit 1');
        $a3 = clone $select1;
        dump($a3->where('type', 1)->first()->toArray());

        dump('credit 2');
        $a4 = clone $select1;
        dump($a4->where('type', 2)->first()->toArray());

        dump('free 9');
        $a4 = clone $select1;
        dump($a4->where('type', 9)->first()->toArray());

        dump('free 10');
        $a5 = clone $select1;
        dump($a5->where('type', 10)->first()->toArray());

        echo '<br>';
        echo '<h2>WITHOUT USERS</h2>';

        $select2 = Transaction::select([DB::raw('sum(sum) as sum_sum'), DB::raw('sum(bonus_sum) as sum_bonus_sum')])
            ->whereNotIn('user_id', $userOffice)
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate);

        dump('withdraw 4');
        $a12 = clone $select2;
        dump($a12->where('type', 4)->first()->toArray());

        dump('deposit 3');
        $a22 = clone $select2;
        dump($a22->where('type', 3)->first()->toArray());

        dump('debit 1');
        $a32 = clone $select2;
        dump($a32->where('type', 1)->first()->toArray());

        dump('credit 2');
        $a42 = clone $select2;
        dump($a42->where('type', 2)->first()->toArray());

        dump('free 9');
        $a42 = clone $select2;
        dump($a42->where('type', 9)->first()->toArray());

        dump('free 10');
        $a52 = clone $select2;
        dump($a52->where('type', 10)->first()->toArray());
        dd('OK');
        //total
        //withdraw 4
        //    "sum_sum" => "-1711.37109"
        //        "sum_bonus_sum" => "0.00000"
        //deposit 3
        //      "sum_sum" => "107072092.43374"
        //        "sum_bonus_sum" => "0.00000"
        //1 debit
        //     "sum_sum" => "-16164.44604"
        //        "sum_bonus_sum" => "-115667.70096"
        //2 credit
        //        "sum_sum" => "17793.30904"
        //        "sum_bonus_sum" => "105050.02266"
        //9 debit free
        //    "sum_sum" => "0.00000"
        //    "sum_bonus_sum" => "0.00000"
        //10 debit free
        //"sum_sum" => "0.00000"
        //"sum_bonus_sum" => "16698.47000"

        //total_user
        //withdraw 4
        //    "sum_sum" => "-1710.37109"
        //    "sum_bonus_sum" => "0.00000"

        //deposit 3
        //    "sum_sum" => "2142.13374"
        //    "sum_bonus_sum" => "0.00000"
        //1 debit
        //"sum_sum" => "-15647.16570"
        //"sum_bonus_sum" => "-115185.88430"
        //2 credit
        //  "sum_sum" => "17004.45200"
        //    "sum_bonus_sum" => "104606.50600"
        //9 debit free
        //    "sum_sum" => "0.00000"
        //    "sum_bonus_sum" => "0.00000"
        //10 debit free
        //   "sum_sum" => "0.00000"
        //    "sum_bonus_sum" => "16472.77000"

        dd(22);
        $user = User::where('id', 2535)->first();
        $class = BonusHelper::getClass(4);
        //dd($class);
        $bonusObject = new $class($user);
        //dd($bonusObject);
        DB::beginTransaction();
        //$act = $bonusObject->setDeposit(2);
        //$act = $bonusObject->setDeposit(3);
        //$act = $bonusObject->close(1);
        $act = $bonusObject->realActivation(['amount' => 20000]);
        if ($act['success'] === false) {
            DB::rollBack();
            if ($act['success'] === false) {
                throw new \Exception($act['message']);
            }
            dd($act);
        }
        DB::commit();
        dd($act);

        dd(Bonus::findOrFail(2));
        $ip = GeneralHelper::visitorIpCloudFlare();
        dd($ip);

        //dd(2);
        $user = User::where('id', 148)->first();

        $class = BonusHelper::getClass($user->bonus_id);

        $bonusObject = new $class($user);
        DB::beginTransaction();
        $act = $bonusObject->setDeposit(2);
        //$act = $bonusObject->setDeposit(3);
        //$act = $bonusObject->close(1);
        if ($act['success'] === false) {
            DB::rollBack();
            if ($act['success'] === false) {
                throw new \Exception($act['message']);
            }
            dd($act);
        }
        DB::commit();

        dd($act);

        dd($request->user());
        dd(2);
//        dd(User::where('id', 2481)->first());
//        $notificationTransactionDeposit = SystemNotification::select([DB::raw('COALESCE(SUM(value), 0) as sum_deposits')])->where('user_id', 2478)
//            ->where('type_id', 2)
//            ->first();
//
//        dd($notificationTransactionDeposit);
        $user = User::where('id', 2482)->first();

        $class = BonusHelper::getClass($user->bonus_id);

        $bonusObject = new $class($user);

        DB::beginTransaction();
        //$act = $bonusObject->realActivation(['amount' => 4]);
        $act = $bonusObject->close(1);
        dd($act);
        if ($act['success'] === false) {
            DB::rollBack();
            dd($act);
        }
        DB::commit();

        dd($act);
        $userBonus = UserBonus::where('id', 5963)->first();

        dd(2);
        $user = $request->user();
        $user = User::where('id', $user->id)->first();
        dd($user);
        $class = BonusHelper::getClass(1);
        $a = new $class();
        //dd(BonusHelper::getClass(1)::id);
        $bonusClasses = config('bonus.classes');
        $user = User::where('id', 136)->first();
        $bonusObject = new $bonusClasses[1]($user);
        $bonusObject->activationAfterTransaction(222);
        dd($bonusObject);
        Mail::queue('emails.confirm', ['link' => 'dsfgfdgfd'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(2);
        $userBonus = UserBonus::where('id', 2225)->first();
        dd($userBonus->data);
        $date = new \DateTime();
        $userBonus = UserBonus::where('id', 2225)->update([
            'data' => json_encode(['wagered_sum' => 0]),
        ]);
        dd(2);
        $date = new \DateTime();
        $bonusUser = UserBonus::create([
            'expires_at' => $date,
            'user_id' => 136,
            'bonus_id' => 1,
            'activated' => 1,
            'data' => [
                'free_spin_win' => 0,
                'wagered_sum' => 0,
                'transaction_id' => 0,
                'dateStart' => $date,
                'lastCheck' => $date,
            ],
        ]);
        dd(2);
        Mail::queue('emails.confirm', ['link' => 'dsfgfdgfd'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(2);
        $ip = GeneralHelper::visitorIpCloudFlare();
        dump($ip);
        $iso_code = GeneralHelper::visitorCountryCloudFlare();
        dd($iso_code);
        dd(GeneralHelper::visitorIpCloudFlare());
        $amount = 5;
        $lastTransaction = Transaction::where('sum', 0)
            ->where(function ($query) {
                $query->where('transactions.sum', '<>', 0)
                    ->orWhere('transactions.bonus_sum', '<>', 0);
            })->first();

        $lastTransaction->sum = -2;
        dump($lastTransaction->toArray());

        if (!is_null($lastTransaction)) {
            //to do! fix this
            $totalSum = abs($lastTransaction->sum + $lastTransaction->bonus_sum);

            $percentageSum = abs($lastTransaction->sum) / $totalSum;
            $createParams['sum'] = GeneralHelper::formatAmount($amount * $percentageSum);

            $percentageBonusSum = abs($lastTransaction->bonus_sum) / $totalSum;
            $createParams['bonus_sum'] = GeneralHelper::formatAmount($amount * $percentageBonusSum);
        } else {
            //to do throw if transactions not found
            $createParams['sum'] = $amount;
            $createParams['bonus_sum'] = 0;
        }
        dd($createParams);
        dd(2);

        $modePlay = 1;
        $amount = -5;
        $transactionHas = (object)['sum' => 10, 'bonus_sum' => 2];

        if ($modePlay === 0) {
            $createParams['sum'] = $amount;
            $createParams['bonus_sum'] = 0;
        } else {
            //to do!! fix this
            $createParams['sum'] = (-1) * $transactionHas->sum;
            $createParams['bonus_sum'] = (-1) * $transactionHas->bonus_sum;
        }
        dd($createParams);

        $modePlay = 1;

        $balance = 1;
        $amount = -2;

        if ($modePlay === 0) {
            $createParams['sum'] = $amount;
            $createParams['bonus_sum'] = 0;
        } else {
            //to do fix this
            if ((float)$balance < abs($amount)) {
                $createParams['sum'] = -1 * $balance;
                $createParams['bonus_sum'] = -1 * GeneralHelper::formatAmount(
                        abs($amount) - abs($createParams['sum']));

//                            } elseif ((float)$params['user']->balance < 0) {
//                                $createParams['sum'] = 0;
//                                $createParams['bonus_sum'] = $amount;
            } else {
                $createParams['sum'] = $amount;
                $createParams['bonus_sum'] = 0;
            }
        }

        dd($createParams);
        $amount = 5;
        $lastTransaction = Transaction::where('sum', 0)->first();
        $lastTransaction->bonus_sum = '2.0';
        $lastTransaction->sum = '1.0';
        dump($lastTransaction->toArray());
        if (!is_null($lastTransaction)) {
            //to do! fix this
            if ((float)$lastTransaction->bonus_sum > 0) {
                $totalSum = abs($lastTransaction->sum + $lastTransaction->bonus_sum);

                $percentageSum = abs($lastTransaction->sum) / $totalSum;
                $createParams['sum'] = GeneralHelper::formatAmount($amount * $percentageSum);

                $percentageBonusSum = abs($lastTransaction->bonus_sum) / $totalSum;
                $createParams['bonus_sum'] = GeneralHelper::formatAmount($amount * $percentageBonusSum);
            } else {
                $createParams['sum'] = $amount;
                $createParams['bonus_sum'] = 0;
            }
        } else {
            //to do throw if transactions not found
            $createParams['sum'] = $amount;
            $createParams['bonus_sum'] = 0;
        }
        dd($createParams);
        dd($t);
        //dd(LastActionGame::where('user_id', 136)->first());
        $activeBonus = UserBonus::where('id', 1129)->first();
        dd($activeBonus->data);
        $activeBonus->data = ['lastCheck' => new \DateTime()];
        $activeBonus->save();

        dd($activeBonus->data);
        $bonusData = $activeBonus->date;
        $bonusData['test'] = 1;
        //$bonusLastAction = $bonusData['lastCheck'];
        $bonusData['lastCheck'] = new \DateTime();
        UserBonus::where('id', 1129)->update(['data' => json_encode($bonusData)]);
        dd(2);
        $activeBonus = UserBonus::where('id', 1129)->first();
        dd($activeBonus->data);
        $notificationTransactionDeposit = SystemNotification::where('user_id', 136)
            ->where('type_id', 1)
            ->where('created_at', '>', $activeBonus->created_at)
            ->first();
        dd($notificationTransactionDeposit);
        if (is_null($notificationTransactionDeposit)) {
            $conditions = 1;
            $response = [
                'success' => true,
                'message' => 'Deposit is not found',
            ];
        }

        dd(2);
        $lastTransaction = Transaction::leftJoin('games_pantallo_transactions',
            'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
            ->where([
                ['games_pantallo_transactions.action_id', '=', 1],
                ['transactions.user_id', '=', 136],
                //['games_pantallo_transactions.games_session_id', '=', $gamesSessionId]
            ])->where(function ($query) {
                $query->where('transactions.sum', '<>', 0)
                    ->orWhere('transactions.bonus_sum', '<>', 0);
            })
            ->select([
                'transactions.id',
                'transactions.*',
                'games_pantallo_transactions.id as ids',
                'action_id',
                'transactions.sum',
                'transactions.bonus_sum',
                'games_pantallo_transactions.amount as amount',
                'games_pantallo_transactions.game_id as game_id',
                'games_pantallo_transactions.balance_after as balance_after',
            ])->orderBy('id', 'DESC')->first();

        dump($lastTransaction->toArray());
        if (!is_null($lastTransaction)) {
            if ((float)$lastTransaction->bonus_sum != 0 and (float)$lastTransaction->sum != 0) {
                $totalSum = abs($lastTransaction->sum + $lastTransaction->bonus_sum);

                $percentageSum = abs($lastTransaction->sum) / $totalSum;
                $createParams['sum'] = GeneralHelper::formatAmount(2 * $percentageSum);

                $percentageBonusSum = abs($lastTransaction->bonus_sum) / $totalSum;
                $createParams['bonus_sum'] = GeneralHelper::formatAmount(2 * $percentageBonusSum);
            } elseif (0 == 0) {
                $createParams['sum'] = 0;
                $createParams['bonus_sum'] = 2;
            } else {
                $createParams['sum'] = 2;
                $createParams['bonus_sum'] = 0;
            }
        } else {
            $createParams['sum'] = 0;
            $createParams['bonus_sum'] = 2;
        }

        dd($createParams);
        $slotsGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where([
                ['games_types_games.extra', '=', 1],
                ['games_list.active', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->whereIn('games_types_games.type_id', [10001])
            ->groupBy('games_types_games.game_id')->get();

        $slotsGameIds = array_map(function ($item) {
            return $item->id;
        }, $slotsGames);

        $typeOpenGame = LastActionGame::select(['id'])
            ->where('user_id', 136)
            ->whereIn('game_id', $slotsGameIds)
            ->first();
        dd($typeOpenGame);

        $typeOpenGame = GamesPantalloSessionGame::join('games_pantallo_session',
            'games_pantallo_session.system_id', '=', 'games_pantallo_session_game.session_id')
            ->where([
                ['games_pantallo_session.user_id', '=', 136],
            ])
            ->whereIn('game_id', $slotsGameIds)
            ->select([
                'games_pantallo_session_game.id',
            ])
            ->orderBy('id', 'desc')
            ->first();
        dd($typeOpenGame);
        $slotsGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where([
                ['games_types_games.extra', '=', 1],
                ['games_list.active', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->whereIn('games_types_games.type_id', [10001])
            ->groupBy('games_types_games.game_id')->get();

        $slotsGameIds = array_map(function ($item) {
            return $item->id;
        }, $slotsGames);

        //to do! use table last action
        $typeOpenGame = LastActionGame::select(['id', 'game_id'])
            ->where('user_id', 136)
            ->whereIn('game_id', $slotsGameIds)
            ->first();
        dd($typeOpenGame);
        DB::enableQueryLog();

        $deposit = SystemNotification::where('user_id', 136)->where('type_id', 1)->count();
        dump($deposit);
        dd(DB::getQueryLog());

        dd($deposit);
        SystemNotification::create([
            'user_id' => 136,
            //to do config - mean deposit transactions
            'type_id' => 1,
            'extra' => json_encode([
                'transactionId' => 1,
                'depositAmount' => 200.200,
            ]),
        ]);
        dd(2);
        $gamesSession = GamesPantalloSessionGame::select(['id', 'game_id'])
            ->where([
                'game_id' => '',
            ])->first();
        dd($gamesSession);

        $transactions = [888048];
        $setAmount = 60;
        $getTransactions = Transaction::whereIn('id', $transactions)->where('type', 4)->get();
        dump($getTransactions);
        foreach ($getTransactions as $transaction) {
            $absTransactionSum = (-1) * $transaction->sum;
            if ($absTransactionSum > $setAmount) {
                Transaction::where('id', $transaction->id)->update([
                    'sum' => -1 * $setAmount,
                ]);
                $difference = GeneralHelper::formatAmount($absTransactionSum - $setAmount);
                $date = new \DateTime();
                Transaction::insert([
                    [
                        'type' => '11',
                        'created_at' => $date,
                        'updated_at' => $date,
                        'deleted_at' => $date,
                        'sum' => -1 * $difference,
                        'user_id' => $transaction->user_id,
                        'comment' => 'system',
                    ],
                ]);
            }
        }
        dd('ok');
        dd(2);
        $transaction = Transaction::leftJoin('games_pantallo_transactions',
            'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
            ->where([
                ['system_id', '=', 'ha-33776d8b2a554bbc8a0628156da2347c'],
                ['games_pantallo_transactions.action_id', '=', 2],
            ])
            ->toSql();
        dd($transaction);

        $service = new Service();
        /*dd(count(        Transaction::where('type', 3)
            ->where('confirmations', '<', 6)
            ->select(['id', 'ext_id', 'confirmations'])->get()));*/
        Transaction::where('type', 3)
            ->where('confirmations', '<', 6)
            ->select(['id', 'ext_id', 'confirmations'])
            ->chunk(100, function ($transactions) use ($service) {
                dd($transactions);
                foreach ($transactions as $transaction) {
                    try {
                        $getTransaction = $service->getTransaction($transaction->ext_id);

                        if ($getTransaction) {
                            Transaction::where('id', $transaction->id)
                                ->update([
                                    'confirmations' => $getTransaction['confirmations'],
                                ]);
                        }
                    } catch (\Exception $ex) {
                        //to do logs and rollback
                        print_r($ex->getMessage());
                    }
                }
            });
        dd(3);

        dd(22);
        $userFields = [
            'users.id as id',
            'users.balance as balance',
            'users.bonus_balance as bonus_balance',
            DB::raw('(users.balance + users.bonus_balance) as full_balance'),
        ];

        //add additional fields
        $additionalFieldsUser = [
            'affiliates.id as partner_id',
            'affiliates.commission as partner_commission',
            'user_bonuses.id as bonus',
            'user_bonuses.bonus_id as bonus_id',
            'user_bonuses.created_at as start_bonus',
            'bonus_n_active.id as bonus_n_active',
            'bonus_n_active.bonus_id as bonus_n_active_id',
            'bonus_n_active.created_at as start_bonus_n_active',
            'bonus_n_active.expires_at as expires_at',
        ];

        $params['user'] = User::select(array_merge($userFields, $additionalFieldsUser))
            ->leftJoin('users as affiliates', 'users.agent_id', '=', 'affiliates.id')
            ->leftJoin('user_bonuses', function ($join) {
                $join->on('users.id', '=', 'user_bonuses.user_id')
                    ->where('user_bonuses.activated', '=', 1)
                    ->whereNull('user_bonuses.deleted_at');
            })
            ->leftJoin('user_bonuses as bonus_n_active', function ($join) {
                $join->on('users.id', '=', 'bonus_n_active.user_id')
                    //bonus_id this for free spins
                    ->where('bonus_n_active.bonus_id', '=', 1)
                    ->whereNull('bonus_n_active.deleted_at');
            })
            ->where([
                ['users.id', '=', 635],
            ])->first();
        dd($params);
        $user = User::where('id', 478)->first();
        $user->bonus_balance = 0;
        dispatch(new BonusHandler($user));
        dd($user);

        DB::beginTransaction();

        try {
            dump($user);
            User::where('id', 136)->update(['bonus_balance' => 10]);
            $user1 = User::where('id', 136)->first();

            dump($user1);

            sleep(10);
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        dd(2);
        $user = User::where('id', 621)->first();
        dispatch(new BonusHandler($user));
        dd(21);
        //to do universal way define user to DO
        $sessionId = $_COOKIE['laravel_session'];
        $sessionLeftTime = config('session.lifetime');
        $sessionLeftTimeSecond = $sessionLeftTime * 60;
        $user = User::where('email', 'alexproc1313@gmail.com')
            ->with('currency')
            ->first();

        //to do this - fix this = use universal way
        $sessionUser = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->where('last_activity', '<=', DB::raw("last_activity + $sessionLeftTimeSecond"))
            ->first();

        /*if (is_null($sessionUser)) {
            return response()->json([
                'status' => false,
                'messages' => ['User or session is not found'],
            ]);
        }*/

        $transaction = $user->transactions()
            ->where('type', 3)->where('notification', 0)->first();

        if ($transaction) {
            $sum = $transaction->sum;
            $transaction->notification = 1;
            $transaction->save();
        } else {
            $sum = false;
        }

        //to do check active bonus
        //to do use dispatch
        dispatch(new BonusHandler($user));
        dd(2);
        $date = new \DateTime();
        dump($date);
        $minimumAllowedActivity = $date->modify('-222 second');
        dd($minimumAllowedActivity);
        dd(2);
        $configIntegratedGames = config('integratedGames.common');

        $whereGameList = [
            ['games_types_games.extra', '=', 1],
            ['games_list.active', '=', 1],
            ['games_types.active', '=', 1],
            ['games_categories.active', '=', 1],
        ];

//        $paginationCount = $configIntegratedGames['listGames']['pagination']['mobile'];
//        array_push($whereGameList, ['games_list.mobile', '=', 1]);

        $paginationCount = $configIntegratedGames['listGames']['pagination']['desktop'];
        array_push($whereGameList, ['games_list.mobile', '=', 0]);

        $list = GamesTypeGame::select([
            0 => 'games_list.id',
            1 => 'games_list_extra.name',
            2 => 'games_list.provider_id',
            3 => 'games_types.name as type',
            4 => 'games_categories.name as category',
            5 => 'games_list_extra.image as image',
            6 => 'games_list.rating',
            7 => 'games_list.active',
            8 => 'games_list.mobile',
            9 => 'games_list.created_at',
        ])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where($whereGameList)
            ->groupBy('games_types_games.game_id')
            ->get()->toArray();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Content-type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=games_all.csv', 'Expires' => '0', 'Pragma' => 'public',
        ];

        // add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);

        dd(2);
        Mail::queue('emails.confirm', ['link' => 'dsfgfdgfd'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(2);
        Mail::send('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(2);
//        $a = RawLog::create([
//            'type_id' => 21,
//            'request' => GeneralHelper::fullRequest(),
//        ]);
        $a = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => 21,
            'request' => GeneralHelper::fullRequest(),
        ]);

        DB::connection('logs')->table('raw_log')->where('id', $a)->update([
            //'user_id' => 22222,
            'response' => 2,
            'extra' => 2,
        ]);
        dd($a);
        $user = User::where('email', 'bekerman.i@blockspoint.com')->first();
        $userNameDefault = $user->id;
        $usePrefixAfter = '2019-04-25 00:00:00';
        $prefixName = 'test';

        $userName = $userNameDefault;

        if ($user->created_at > $usePrefixAfter) {
            $userName = $prefixName . $userNameDefault;
        }

        $prefixNameStrictly = ModernExtraUsers::select(['user_id', 'code', 'value'])
            ->where('user_id', $user->id)
            ->where('code', 'prefixName')
            ->first();

        if (!is_null($prefixNameStrictly)) {
            $userName = $prefixNameStrictly->value . $userNameDefault;
        }

        dd($userName);

        //add user for request - for lib
        $request->merge(['user' => $user]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        //get games for free spins
        $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->whereIn('games_types_games.type_id', [10001])
            ->where([
                ['games_list.active', '=', 1],
                ['games_list.free_round', '=', 1],
                ['games_types_games.extra', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->groupBy('games_types_games.game_id')->get();

        $gamesIds = implode(',', array_map(function ($item) {
            return $item->system_id;
        }, $freeRoundGames));

        $pantalloGamesSystem = new PantalloGamesSystem();
        $freeRound = $pantalloGamesSystem->freeRound($request);

        dd($freeRound);
        $request->merge(['gamesIds' => $gamesIds]);
        $request->merge(['available' => 50]);
        $request->merge(['timeFreeRound' => strtotime('30 day', 0)]);
        dd(2);
        //dd(GeneralHelper::visitorIpCloudFlare());
//        dd(2);
//        $user = User::where('email', 'tafuzijos@blackbird.ws')->first();
//        dd($user);
//        $date = new \DateTime();
//
//        $balanceUser = $user->balance;
//
//        User::where('id', $user->id)->update([
//            'balance' => 0
//        ]);
//
//        Transaction::insert([
//            [
//                'type' => '11',
//                'created_at' => $date,
//                'updated_at' => $date,
//                'deleted_at' => $date,
//                'sum' => -1 * $balanceUser,
//                'user_id' => $user->id,
//                'comment' => 'system balance'
//            ],
//        ]);
        dd(2);
        $transactions = [234575];
        $setAmount = 60;
        $getTransactions = Transaction::whereIn('id', $transactions)->where('type', 4)->get();
        dump($getTransactions);
        foreach ($getTransactions as $transaction) {
            $absTransactionSum = (-1) * $transaction->sum;
            if ($absTransactionSum > $setAmount) {
                Transaction::where('id', $transaction->id)->update([
                    'sum' => -1 * $setAmount,
                ]);
                $difference = GeneralHelper::formatAmount($absTransactionSum - $setAmount);
                $date = new \DateTime();
                Transaction::insert([
                    [
                        'type' => '11',
                        'created_at' => $date,
                        'updated_at' => $date,
                        'deleted_at' => $date,
                        'sum' => -1 * $difference,
                        'user_id' => $transaction->user_id,
                        'comment' => 'system',
                    ],
                ]);
            }
        }
        dd('ok');
        dd(22);

        $bonuses = UserBonus::where('user_id', 680)->get();

        foreach ($bonuses as $bonus) {
            $class = $bonus->bonus->getClass();
            $bonus_obj = new $class($bonus->user);

            try {
                dump($bonus_obj->realActivation());
                dump($bonus_obj->close());
            } catch (\Exception $e) {
                dd([
                    'id' => $bonus->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        dd(2);
        dd(GeneralHelper::visitorIpCloudFlare());
        $transactions = [385460];
        $setAmount = 60;
        $getTransactions = Transaction::whereIn('id', $transactions)->where('type', 4)->get();

        foreach ($getTransactions as $transaction) {
            $absTransactionSum = (-1) * $transaction->sum;
            if ($absTransactionSum > $setAmount) {
                Transaction::where('id', $transaction->id)->update([
                    'sum' => -1 * $setAmount,
                ]);
                $difference = GeneralHelper::formatAmount($absTransactionSum - $setAmount);
                $date = new \DateTime();
                Transaction::insert([
                    [
                        'type' => '11',
                        'created_at' => $date,
                        'updated_at' => $date,
                        'deleted_at' => $date,
                        'sum' => -1 * $difference,
                        'user_id' => $transaction->user_id,
                        'comment' => 'system',
                    ],
                ]);
            }
        }
        dd('ok');
        dd(2);
        $transactions = Transaction::where('confirmations', '<', 2)->where('type', 3)->get();
        foreach ($transactions as $item) {
            dump($item->id);
            dump($item->ext_id);
            echo '<br>';
        }
        dd($transactions);

        $transactions = [];
        $setAmount = 60;
        $getTransactions = Transaction::whereIn('id', $transactions)->where('type', 4)->get();
        foreach ($getTransactions as $transaction) {
            $absTransactionSum = (-1) * $transaction->sum;
            if ($absTransactionSum > $setAmount) {
                Transaction::where('id', $transaction->id)->update([
                    'sum' => -1 * $setAmount,
                ]);
                $difference = GeneralHelper::formatAmount($absTransactionSum - $setAmount);
                $date = new \DateTime();
                Transaction::insert([
                    [
                        'type' => '11',
                        'created_at' => $date,
                        'updated_at' => $date,
                        'deleted_at' => $date,
                        'sum' => -1 * $difference,
                        'user_id' => $transaction->user_id,
                        'comment' => 'system',
                    ],
                ]);
            }
        }
        dd('ok');
        //$amount = $getTransaction
        Transaction::where('id', 307311)->where('type', 4)->update([
            'sum' => '-60',
        ]);
        //create new transaction

        dd($getTransaction);
        dd(2);
        $ip = GeneralHelper::visitorIpCloudFlare();
        //dump($ip);
        //$ip = '165.227.71.60';
        //to do this job edit session way

        // !!! No more torann/geoip !!!
        // Use GeneralHelper::visitorCountryCloudFlare
        $ip = geoip($ip);

        dd($ip);
        DB::enableQueryLog();
        $user = User::where('id', 1031)->first();
        $transaction = $user->transactions()
            ->where('type', 3)->where('notification', 0)->first();
        dd(DB::getQueryLog());
        dd(2);
        $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->whereIn('games_types_games.type_id', [10001])
            ->where([
                ['games_list.active', '=', 1],
                ['games_list.free_round', '=', 1],
                ['games_types_games.extra', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->groupBy('games_types_games.game_id')->get();

        $gamesIds = implode(',', array_map(function ($item) {
            return $item->system_id;
        }, $freeRoundGames));

        $request->merge(['gamesIds' => $gamesIds]);
        $request->merge(['available' => 50]);
        $request->merge(['timeFreeRound' => strtotime('5 day', 0)]);

        $user = User::where('id', 1031)->first();
        $pantalloGamesSystem = new PantalloGamesSystem();
        $freeRound = $pantalloGamesSystem->freeRound($request, $user);
        dd($freeRound);
        dd(2);
        $users = User::where('id', '>', 450)->get();
        foreach ($users as $user) {
            User::where('id', $user->id)->update([
                'email_confirmed' => 1,
            ]);
        }
        dd(2);
        //peho@max-mail.info
        Mail::queue('emails.confirm', ['link' => 'dsfgfdgfd'], function ($m) use ($user) {
            $m->to('alexproc1313@gmail.com', $user->name)->subject('Confirm email');
        });
        dd(2);
        Mail::send('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });

        $timeKeepLang = config('appAdditional.keepLanguage');
        $prefixLang = $request->route()->parameter('lang');
        $cookieLang = Cookie::get('lang');
        $currentLocale = app()->getLocale();
        $lang = GeneralHelper::getLang($prefixLang, $cookieLang, $currentLocale);
        dd($lang);

        dd(2);
        $pantalloGames = new PantalloGames;
        $allGames = $pantalloGames->getGameList([], true);
        dd($allGames);
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Content-type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=games.csv', 'Expires' => '0', 'Pragma' => 'public',
        ];

        $list = GamesTypeGame::select(['games_list_extra.name as origin_name', 'games_categories.name as provider_name'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->whereIn('games_types_games.type_id', [10001])
            ->where([
                ['games_list.active', '=', 1],
                ['games_list.free_round', '=', 1],
                ['games_types_games.extra', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->groupBy('games_types_games.game_id')->get()->toArray();

        // add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
        dd(2);
        Mail::send('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });

        dd(2);
        $transactionHas = Transaction::leftJoin('games_pantallo_transactions',
            'games_pantallo_transactions.transaction_id', '=', 'transactions.id')
            ->where([
                ['system_id', '=', 'gs-1006024717-9db8bb'],
            ])->select([
                'transactions.id',
                'transactions.sum',
                'transactions.bonus_sum',
                'action_id',
                DB::raw('(transactions.sum + transactions.bonus_sum) as real_amount'),
                'games_pantallo_transactions.amount as amount',
                'games_pantallo_transactions.game_id as game_id',
                'games_pantallo_transactions.balance_after as balance_after',
            ])->first();
        dd($transactionHas);
        $user = User::where('id', 136)->first();
        $d = $user->created_at;
        $d1 = $d->modify('+3 days');
        $dd = $user->created_at;
        $d2 = $dd->modify('+100 days');
        dd($d1, $d2);
        $bonuses = UserBonus::all();

        foreach ($bonuses as $bonus) {
            $class = $bonus->bonus->getClass();
            $bonus_obj = new $class($bonus->user);

            try {
                $bonus_obj->realActivation();
                $bonus_obj->close();
            } catch (\Exception $e) {
                Log::alert([
                    'id' => $bonus->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        dd(2);
        $freeSpinWin = DB::table('transactions')->where('user_id', 157)->where([
            ['created_at', '>', '2019-03-05 16:49:03'],
            ['type', '=', 10],
        ])->get();
        dd($freeSpinWin);
        dump($_SERVER['REMOTE_ADDR']);
        dd($request->server('REMOTE_ADDR'));
        $client = new Client([
            'verify' => false,
        ]);

        //https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA
        $response = $client->post('https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA', [
            'form_params' => [
                'grant_type' => 'password',
                'response_type' => 'token',
                'username' => 'api_casinobit',
                'password' => 'BfRN18uA',
            ],
        ]);
        $json = $response->getBody()->getContents();
        $json = json_decode($json);
        dd(2);

        try {
            $response = $client->get('https://api-int.qtplatform.com/v1/games', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $json->access_token,
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (\Exception $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            return $responseBodyAsString;
            dd($responseBodyAsString);
        }

        $game = $response->getBody()->getContents();
        $game = json_decode($game);
        foreach ($game->items as $game) {
            foreach ($game->currencies as $currency) {
                if ($currency == 'MBTC') {
                    dump($game);
                }
            }
        }
        dd(2);
//        DB::enableQueryLog();
//        $bonuses = UserBonus::where('id', 1114)->update(['activated' => 0]);
//        dd(DB::getQueryLog());
//        dd(2);

        $bonuses = UserBonus::all();
        foreach ($bonuses as $bonus) {
            $class = $bonus->bonus->getClass();
            $bonus_obj = new $class($bonus->user);

            try {
                //$bonus_obj->realActivation();
                $bonus_obj->close();
            } catch (\Exception $e) {
                Log::alert([
                    'id' => $bonus->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        dd(21);

        try {
            $typeBonus = 1;
            $bonusClass = null;
            $bonusLimit = $bonusClass::$maxAmount;
        } catch (\Exception $x) {
            dd(1);
        }

        $userFields = [
            'users.id as id',
            'users.balance as balance',
            'users.bonus_balance as bonus_balance',
            DB::raw('(users.balance + users.bonus_balance) as full_balance'),
        ];

        //add additional fields
        $additionalFieldsUser = [
            'affiliates.id as partner_id',
            'affiliates.commission as partner_commission',
            'user_bonuses.id as bonus',
            'user_bonuses.bonus_id as bonus_id',
            'user_bonuses.created_at as start_bonus',
            'bonus_not_active.id as bonus_n_active',
            'bonus_not_active.bonus_id as bonus_n_active_id',
            'bonus_not_active.created_at as start_bonus_n_active',
        ];

        $params['user'] = User::select(array_merge($userFields, $additionalFieldsUser))
            ->leftJoin('users as affiliates', 'users.agent_id', '=', 'affiliates.id')
            ->leftJoin('user_bonuses', function ($join) {
                $join->on('users.id', '=', 'user_bonuses.user_id')
                    ->where('user_bonuses.activated', '=', 1)
                    ->whereNull('user_bonuses.deleted_at');
            })
            ->leftJoin('user_bonuses as bonus_not_active', function ($join) {
                $join->on('users.id', '=', 'bonus_not_active.user_id')
                    ->where('bonus_not_active.activated', '=', 0);
                //->whereNull('user_bonuses.deleted_at');
            })
            ->where([
                ['users.id', '=', 136],
            ])->first();
        dd($params);

        dd(Auth::user());
        dd(20);
        $params = [];
        $userFields = [
            'users.id as id',
            'users.balance as balance',
            'users.bonus_balance as bonus_balance',
            DB::raw('(users.balance + users.bonus_balance) as full_balance'),
        ];

        //add additional fields
        $additionalFieldsUser = [
            'affiliates.id as partner_id',
            'affiliates.commission as partner_commission',
            'user_bonuses.id as bonus',
            'user_bonuses.bonus_id as bonus_id',
            'user_bonuses.data as data',
        ];

        $params['user'] = User::select(array_merge($userFields, $additionalFieldsUser))
            ->leftJoin('users as affiliates', 'users.agent_id', '=', 'affiliates.id')
            ->leftJoin('user_bonuses', function ($join) {
                $join->on('users.id', '=', 'user_bonuses.user_id')
                    ->where('user_bonuses.activated', '=', 1)
                    ->whereNull('user_bonuses.deleted_at');
            })
            ->where([
                ['users.id', '=', 155],
            ])->first();

        dd($params['user']);
        dd(json_decode($params['user']->data));
        dd(2);

        dd(config('appAdditional.minConfirmBtc'));
        //ini_set('max_execution_time', 600);
        $games = GamesList::all();
        foreach ($games as $game) {
            GamesListExtra::where('game_id', $game->id)->update([
                'category_id' => $game->category_id,
            ]);
        }
        dd('Ok');
        $games = GamesList::where('details', null)->get();
        dd($games);

        $pantalloGamesSystem = new PantalloGamesSystem();
        $freeRound = $pantalloGamesSystem->removeFreeRounds($request);
        dd($freeRound);

        $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->whereIn('games_types_games.type_id', [10001])
            ->where([
                ['games_types_games.extra', '=', 1],
                ['games_list.active', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->groupBy('games_types_games.game_id')->get();

        $gamesIds = implode(',', array_map(function ($item) {
            return $item->system_id;
        }, $freeRoundGames));

        $request->merge(['gamesIds' => $gamesIds]);
        $request->merge(['available' => 1]);
        $request->merge(['timeFreeRound' => strtotime('1 day', 0)]);

        $pantalloGamesSystem = new PantalloGamesSystem();
        $freeRound = $pantalloGamesSystem->freeRound($request);
        dd($freeRound);
        dd(2);

        DB::beginTransaction();
        RawLog::create([
            'type_id' => 4,
            'request' => 4,
            'response' => 4,
            'extra' => 4,
        ]);

        if (1) {
            DB::beginTransaction();
            RawLog::create([
                'type_id' => 1,
                'request' => 1,
                'response' => 1,
                'extra' => 1,
            ]);
            DB::commit();
        }
        DB::commit();

        dd(2);

        RawLog::create([
            'type_id' => 1,
            'request' => GeneralHelper::fullRequest(),
            'response' => 2,
            'extra' => 2,
        ]);
        dd(2);
        $slotTypeId = config('appAdditional.slotTypeId');
        $slotsGame = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->whereIn('games_types_games.type_id', [$slotTypeId])
            ->where([
                ['games_list.system_id', '=', 12545],
                ['games_types_games.extra', '=', 1],
                ['games_list.active', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])->groupBy('games_types_games.game_id')->first();
        dd($slotsGame);
        $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->whereIn('games_types_games.type_id', [10001])
            ->where([
                ['games_types_games.extra', '=', 1],
                ['games_list.active', '=', 1],
                ['games_types.active', '=', 1],
                ['games_categories.active', '=', 1],
            ])
            ->groupBy('games_types_games.game_id')->get();

        $freeRoundGames = array_map(function ($item) {
            return $item->id;
        }, $freeRoundGames);

        $openGames = GamesPantalloSessionGame::join('games_pantallo_session',
            'games_pantallo_session.system_id', '=', 'games_pantallo_session_game.session_id')
            ->whereIn('games_pantallo_session_game.game_id', $freeRoundGames)
            ->where([
                ['games_pantallo_session.user_id', '=', 136],
            ])->first();
        dd($openGames);

        dd('old');

        return redirect('/')->with('popup_fixed', 'true');

        $service = new Service();

        $data = $service->getWalletInfo();
        dd(2);
        $service = new Service();
        //dd($service);
        $address = $service->info();
        dd($address);
        Mail::queue('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(url('/'));
        $service = new Service();
        dd($service);
        $address = $service->getNewAddress('common');
        dd(2);
        $bonuses = UserBonus::all();
        foreach ($bonuses as $bonus) {
            $class = $bonus->bonus->getClass();
            $bonus_obj = new $class($bonus->user);

            try {
                $bonus_obj->realActivation();
                $bonus_obj->close();
            } catch (\Exception $e) {
                Log::alert([
                    'id' => $bonus->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        dd(23);

//        $a = UserBonus::withTrashed()->where('user_id', 75)->first();
//        dd($a->data);
//        //GamesTypeGame
//        $freeRoundGames = DB::table('games_types_games')->select(['games_list.id', 'games_list.system_id'])
//            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
//            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
//            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
//            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
//            ->whereIn('games_types_games.type_id', [10001])
//            ->where([
//                ['games_types_games.extra', '=', 1],
//                ['games_list.active', '=', 1],
//                ['games_types.active', '=', 1],
//                ['games_categories.active', '=', 1],
//            ])
//            ->groupBy('games_types_games.game_id')->get();
//
//        $gamesIds = implode(',', array_map(function ($item) {
//            return $item->system_id;
//        }, $freeRoundGames));
//        dd($gamesIds);
//
//        $request->merge(['gamesIds' => '12545']);
//        $request->merge(['available' => 1]);
//        $request->merge(['timeFreeRound' => strtotime("$this->expireDays day", 0)]);
//
//        $pantalloGamesSystem = new PantalloGamesSystem();
//        $freeRound = $pantalloGamesSystem->freeRound($request);
//
//        dd(2);
        $bonuses = UserBonus::all();

        foreach ($bonuses as $bonus) {
            $class = $bonus->bonus->getClass();
            $bonus_obj = new $class($bonus->user);
            $bonus_obj->realActivation();
            //$bonus_obj->close();
        }

        dd(1);
        $transaction = $request->user()->transactions()->where([
            ['type', '=', 10],
        ])->orderBy('id', 'DESC')->first();
        dd($transaction);
        //User::where('id',136)->update(['balance' => 138]);
        //dd(2);
        $configFreeRounds = config('appAdditional.freeRounds');
        $request->merge(['gamesIds' => '12545,2057']);
        $request->merge(['available' => 4]);
        $request->merge(['timeFreeRound' => $configFreeRounds['timeFreeRound']]);

        $pantalloGamesSystem = new PantalloGamesSystem();

        $response = $pantalloGamesSystem->freeRound($request);
        dd($response);
        $wager_transaction = Transaction::where('type', 1)->orderBy('id', 'DESC')->where(function ($query) {
            $query->where('sum', '<>', 0)->orWhere('bonus_sum', '<>', 0);
        })->first();
        dd($wager_transaction);
        Mail::queue('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m) {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(url('/'));

        $now = Carbon::now();
        dd($now);
        dd($now->format('U'));

        dd(2);
        $client = new Client([
            'verify' => false,
        ]);
        //https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA
        $response = $client->post('https://api-int.qtplatform.com/v1/auth/token?grant_type=password&response_type=token&username=api_casinobit&password=BfRN18uA', [
            'form_params' => [
                'grant_type' => 'password',
                'response_type' => 'token',
                'username' => 'api_casinobit',
                'password' => 'BfRN18uA',
            ],
        ]);
        $json = $response->getBody()->getContents();
        $json = json_decode($json);

        try {
            $response = $client->get('https://api-int.qtplatform.com/v1/games', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $json->access_token,
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (\Exception $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            return $responseBodyAsString;
            dd($responseBodyAsString);
        }

        $game = $response->getBody()->getContents();
        $game = json_decode($game);
        foreach ($game->items as $game) {
            foreach ($game->currencies as $currency) {
                if ($currency == 'MBTC') {
                    dump($game);
                }
            }
        }
        dd(2);
        $service = new Service();

        $data = $service->info();
        dd($data);
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
                'updated_at' => DB::raw('updated_at'),]);
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
                ['games_types_games.type_id', '=', 1],
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
            300, 639, 640, 642, 643, 644, 645, 646, 647, 648, 653, 654, 655, 656, 657, 658, 659, 660, 661, 662, 663, 664, 665, 666, 667, 668, 669, 670, 671, 672, 673, 674, 680, 681, 682, 683, 686, 687, 688, 689, 690, 693, 696, 695, 694, 697, 698, 699, 700, 701, 942, 943, 944, 945, 946, 947, 948, 949, 950, 951, 952, 953, 954, 955, 956, 957, 958, 959, 960, 961, 962, 963, 964, 965, 966, 967, 968, 969, 970, 971, 972, 973, 974, 975, 976, 977, 978, 979, 980, 981, 982, 983,
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
                ['games_types_games.type_id', '=', 5],
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
                'balance' => DB::raw("balance+$am"),
            ]);
        dd(2);

        $test = User::where('id', 1)->update([
            'balance' => DB::raw("balance+$am"),
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
            'balance' => -1,
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
            ],
        ]);
        dd($result->getBody());

        return view('testtest');
    }

    public function testTypes(Request $request)
    {
        dd(2);
        //method is no longer supported!!!!!!!!!!!!!!!!!!!!!
        $games = GamesList::leftJoin('games_types', 'games_types.id', '=', 'games_list.type_id')
            ->where([
                ['games_types.code', '=', $request->category],
            ])->select(['games_types.id', 'games_list.name'])->get();

        return view('test.listGames')->with(['games' => $games]);
    }

    public function game(Request $request)
    {
        dd(2);
        //method is no longer supported!!!!!!!!!!!!!!!!!!!!!
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
                    'password' => self::PASSWORD,
                ], true);
            } else {
                $player = $playerExists;
            }

            //login request
            $login = $pantalloGames->loginPlayer([
                'user_id' => $userId,
                'user_username' => $userId,
                'password' => self::PASSWORD,
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
                'gamesession_id' => $getGame->gamesession_id,]);

            return view('testtest', ['link' => $getGame]);
        } catch (\Exception $e) {
            dd($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
