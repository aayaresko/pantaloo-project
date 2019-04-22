<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\RawLog;
use DB;
use Auth;
use Response;
use Log;
use App\Bitcoin\Service;
use App\Transaction;
use App\Country;
use App\User;
use Validator;
use App\UserBonus;
use Helpers\GeneralHelper;
use App\Models\GamesType;
use App\Models\GamesList;
use App\Models\GamesListExtra;
use App\Models\GamesCategory;
use App\Modules\PantalloGames;
use GuzzleHttp\Client;
use App\Models\Pantallo\GamesPantalloSession;
use App\Models\Pantallo\GamesPantalloSessionGame;
use Illuminate\Http\Request;
use App\Models\GamesTypeGame;
use App\Modules\Games\PantalloGamesSystem;


class TestController extends Controller
{
    const PASSWORD = 'rf3js1Q';

    public function test(Request $request)
    {
        dd(2);
        $transactions = [];
        $setAmount = 60;
        $getTransactions = Transaction::whereIn('id', $transactions)->where('type', 4)->get();
        foreach ($getTransactions as $transaction) {
            $absTransactionSum = (-1)*$transaction->sum;
            if ($absTransactionSum > $setAmount) {
                Transaction::where('id', $transaction->id)->update([
                    'sum' => -1 * $setAmount
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
                        'comment' => 'system'
                    ],
                ]);
            }
        }
        dd('ok');
        //$amount = $getTransaction
        Transaction::where('id', 307311)->where('type', 4)->update([
            'sum' => '-60'
        ]);
        //create new transaction

        dd($getTransaction);
        dd(2);
        $ip = GeneralHelper::visitorIpCloudFire();
        //dump($ip);
        //$ip = '165.227.71.60';
        //to do this job edit session way
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
        $request->merge(['timeFreeRound' => strtotime("5 day", 0)]);

        $user = User::where('id', 1031)->first();
        $pantalloGamesSystem = new PantalloGamesSystem();
        $freeRound = $pantalloGamesSystem->freeRound($request, $user);
        dd($freeRound);
        dd(2);
        $users = User::where('id', '>', 450)->get();
        foreach ($users as $user) {
            User::where('id', $user->id)->update([
                'email_confirmed' => 1
            ]);
        }
        dd(2);
        //peho@max-mail.info
        Mail::queue('emails.confirm', ['link' => 'dsfgfdgfd'], function ($m) use ($user) {
            $m->to('alexproc1313@gmail.com', $user->name)->subject('Confirm email');
        });
        dd(2);
        Mail::send('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m)  {
            $m->to('alexproc1313@gmail.com', 'alexproc')->subject('Confirm email');
        });
        dd(2);
        $pantalloGames = new PantalloGames;
        $allGames = $pantalloGames->getGameList([], true);
        dd($allGames);
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=games.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
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

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
        dd(2);
        Mail::send('emails.partner.confirm', ['link' => 'https://www.google.com/'], function ($m)  {
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
                'games_pantallo_transactions.balance_after as balance_after'
            ])->first();
        dd($transactionHas);
        $user = User::where('id', 136)->first();
        $d = $user->created_at;
        $d1 = $d->modify("+3 days");
        $dd = $user->created_at;
        $d2 = $dd->modify("+100 days");
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
                    'error' => $e->getMessage()
                ]);
            }
        }
        dd(2);
        $freeSpinWin = DB::table('transactions')->where('user_id', 157)->where([
            ['created_at', '>', '2019-03-05 16:49:03'],
            ['type', '=', 10]
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
                'password' => 'BfRN18uA'
            ]
        ]);
        $json = $response->getBody()->getContents();
        $json = json_decode($json);
        dd(2);
        try {
            $response = $client->get('https://api-int.qtplatform.com/v1/games', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $json->access_token,
                    'Accept' => 'application/json',
                ]
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
                    'error' => $e->getMessage()
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
                'category_id' => $game->category_id
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
        $request->merge(['timeFreeRound' => strtotime("1 day", 0)]);

        $pantalloGamesSystem = new PantalloGamesSystem();
        $freeRound = $pantalloGamesSystem->freeRound($request);
        dd($freeRound);
        dd(2);

        DB::beginTransaction();
        RawLog::create([
            'type_id' => 4,
            'request' => 4,
            'response' => 4,
            'extra' => 4
        ]);

        if (1) {

            DB::beginTransaction();
            RawLog::create([
                'type_id' => 1,
                'request' => 1,
                'response' => 1,
                'extra' => 1
            ]);
            DB::commit();
        }
        DB::commit();


        dd(2);


        RawLog::create([
            'type_id' => 1,
            'request' => GeneralHelper::fullRequest(),
            'response' => 2,
            'extra' => 2
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
                    'error' => $e->getMessage()
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
                'password' => 'BfRN18uA'
            ]
        ]);
        $json = $response->getBody()->getContents();
        $json = json_decode($json);

        try {
            $response = $client->get('https://api-int.qtplatform.com/v1/games', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $json->access_token,
                    'Accept' => 'application/json',
                ]
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
