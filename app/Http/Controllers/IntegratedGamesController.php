<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\UserBonus;
use App\Slots\Casino;
use App\Models\GamesList;
use App\Models\GamesType;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Models\GamesListSettings;
use App\Providers\JsBridge\JsBridge;
use Illuminate\Support\Facades\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

/**
 * Class IntegratedGamesController.
 */
class IntegratedGamesController extends Controller
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $relatedFields;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * IntegratedGamesController constructor.
     */
    public function __construct()
    {
        $this->params['settings'] = ['id', 'code', 'value'];

        $this->fields = [
            0 => 'games_list.id',
            1 => 'games_list_extra.name',
            2 => 'games_list.provider_id',
            3 => 'games_types_games.type_id',
            4 => 'games_list_extra.category_id',
            5 => 'games_list_extra.image',
            6 => 'games_list.rating',
            7 => 'games_list.active',
            8 => 'games_list.mobile',
            9 => 'games_list.created_at',
        ];

        $this->relatedFields = $this->fields;
        //$this->relatedFields[2] = 'games_list.provider_id as provider';
        $this->relatedFields[3] = 'games_types.name as type';
        $this->relatedFields[4] = 'games_categories.name as category';
        $this->relatedFields[5] = 'games_list_extra.image as image';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $lang, $type_name = '')
    {
        MetaTag::set('title', trans("metatag.games_{$type_name}_title"));
        MetaTag::set('description', trans("metatag.games_{$type_name}_description"));

        $configIntegratedGames = config('integratedGames.common');
        $appAdditional = config('appAdditional');
        $dummyPicture = $configIntegratedGames['dummyPicture'];
        $defaultTypes = $appAdditional['defaultTypes'];
        $defaultTitle = $appAdditional['defaultTitle'];

        View::share('dummyPicture', $dummyPicture);
        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->params['settings'])->get()->pluck('value', 'code');

        $title = $defaultTitle;

        // Set flag
        $need_redirect = true;

        // flag checking entered value
        $entered_value = false;

        // Check slug
        if ($type_name) {
            $type_name = str_replace('-', ' ', $type_name);
            foreach ($defaultTypes as $defaultType) {
                if ($defaultType['name'] == $type_name) {
                    $entered_value = true;
                    $title = $defaultType['name'];
                    $need_redirect = false;         // Has correct slug, reset redirect flag
                    app(jsBridge::class)['games_type_id'] = $defaultType['id'];
                }
            }
        }

        if ($need_redirect) {
            $type_id = $request->filled('type_id') ? $request->type_id : $type_name;
            foreach ($defaultTypes as $defaultType) {
                if ($defaultType['id'] == $type_id) {
                    $entered_value = true;

                    return redirect()->route('games', [
                        'lang' => $lang,
                        'type_name' => str_replace(' ', '-', $defaultType['name']),
                    ], 301);
                }
            }
        }

        $orderType = ['games_types.rating', 'desc'];
        if (isset($settings['types'])) {
            $orderType = $definitionSettings[$settings['types']];
            $orderType[0] = 'games_types.'.$orderType[0];
        }

        $orderCategoty = ['games_categories.rating', 'desc'];
        if (isset($settings['types'])) {
            $orderCategoty = $definitionSettings[$settings['categories']];
            $orderCategoty[0] = 'games_categories.'.$orderCategoty[0];
        }

        $codeCountry = GeneralHelper::visitorCountryCloudFlare();

        $whereGame = [
            ['games_types_games.extra', '=', 1],
            ['games_list.active', '=', 1],
            ['games_types.active', '=', 1],
            ['games_categories.active', '=', 1],
        ];

        if (Casino::isMobile()) {
            array_push($whereGame, ['games_list.mobile', '=', 1]);
        } else {
            array_push($whereGame, ['games_list.mobile', '=', 0]);
        }

//        $selectTypeFields = [
//            'games_types.id',
//            'games_types.code',
//            'games_types.name',
//            'games_types.rating',
//        ];
//
//        $gamesTypes = DB::table('games_types_games')->select($selectTypeFields)
//            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
//            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
//            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
//            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
//            ->leftJoin('restriction_games_by_country as rg_n', function ($join) use ($codeCountry) {
//                $join->on('rg_n.game_id', '=', 'games_list.id')
//                    ->where('rg_n.code_country', '=', $codeCountry)
//                    ->where('rg_n.mark', '=', 0);
//            })
//            ->leftJoin('restriction_categories_by_country as rc_n', function ($join) use ($codeCountry) {
//                $join->on('rc_n.category_id', '=', 'games_list_extra.category_id')
//                    ->where('rc_n.code_country', '=', $codeCountry)
//                    ->where('rc_n.mark', '=', 0);
//            })
//            ->leftJoin('restriction_games_by_country as rg', function ($join) use ($codeCountry) {
//                $join->on('rg.game_id', '=', 'games_list.id')
//                    ->where('rg.mark', '=', 1);
//            })
//            ->leftJoin('restriction_categories_by_country as rc', function ($join) use ($codeCountry) {
//                $join->on('rc.category_id', '=', 'games_list_extra.category_id')
//                    ->where('rc.mark', '=', 1);
//            })
//            ->where($whereGame)
//            ->whereRaw("(instr((select group_concat(code_country, '') from restriction_games_by_country" .
//                " where game_id = games_list.id), '$codeCountry') OR rg.id is null) AND (rg_n.id is null)")
//            ->whereRaw("(instr((select group_concat(code_country, '') from restriction_categories_by_country" .
//                " where category_id = games_list_extra.category_id), '$codeCountry') OR rc.id is null) " .
//                "AND (IF(instr((select group_concat(code_country, '') from restriction_games_by_country where game_id = games_list.id " .
//                "and code_country = '$codeCountry'), '$codeCountry'), null, rc_n.id) is null)")
//            ->groupBy('games_types_games.type_id')
//            ->orderBy($orderType[0], $orderType[1])->get();

        $currentUser = $request->user();
        $emailsShowAllGames = config('appAdditional.emailsShowAllGames');

        if (! is_null($currentUser) and in_array($currentUser->email, $emailsShowAllGames)) {
            $gamesCategories = GamesCategory::where([
                ['active', '=', 1],
            ])->orderBy($orderCategoty[0], $orderCategoty[1])->get();
        } else {
            $selectCategoryFields = [
                'games_categories.id',
                'games_categories.code',
                'games_categories.name',
                'games_categories.rating',
            ];

            //to do get this date from js
            $gamesCategories = DB::table('games_types_games')->select($selectCategoryFields)
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->leftJoin('restriction_games_by_country as rg_n', function ($join) use ($codeCountry) {
                    $join->on('rg_n.game_id', '=', 'games_list.id')
                        ->where('rg_n.code_country', '=', $codeCountry)
                        ->where('rg_n.mark', '=', 0);
                })
                ->leftJoin('restriction_categories_by_country as rc_n', function ($join) use ($codeCountry) {
                    $join->on('rc_n.category_id', '=', 'games_list_extra.category_id')
                        ->where('rc_n.code_country', '=', $codeCountry)
                        ->where('rc_n.mark', '=', 0);
                })
                ->leftJoin('restriction_games_by_country as rg', function ($join) use ($codeCountry) {
                    $join->on('rg.game_id', '=', 'games_list.id')
                        ->where('rg.mark', '=', 1);
                })
                ->leftJoin('restriction_categories_by_country as rc', function ($join) use ($codeCountry) {
                    $join->on('rc.category_id', '=', 'games_list_extra.category_id')
                        ->where('rc.mark', '=', 1);
                })
                ->where($whereGame)
                ->whereRaw("(instr((select group_concat(code_country, '') from restriction_games_by_country".
                    " where game_id = games_list.id), '$codeCountry') OR rg.id is null) AND (rg_n.id is null)")
                ->whereRaw("(instr((select group_concat(code_country, '') from restriction_categories_by_country".
                    " where category_id = games_list_extra.category_id), '$codeCountry') OR rc.id is null) ".
                    "AND (IF(instr((select group_concat(code_country, '') from restriction_games_by_country where game_id = games_list.id ".
                    "and code_country = '$codeCountry'), '$codeCountry'), null, rc_n.id) is null)")
                ->groupBy('games_categories.id')
                ->orderBy($orderCategoty[0], $orderCategoty[1])->get();
        }

        $gamesTypes = GamesType::where([
            ['active', '=', 1],
        ])->orderBy($orderType[0], $orderType[1])->get();

        if (is_null($currentUser)) {
            $freeSpins = 0;
        } else {
            //to do
            $idFreeSpinsBonus = 1;
            $freeSpinsBonus = UserBonus::where('user_id', $currentUser->id)
                ->where('bonus_id', $idFreeSpinsBonus)->first();
            $freeSpins = (is_null($freeSpinsBonus)) ? 0 : 1;
        }

        if ($entered_value == false && $type_name != '') {
            abort(404);
        }

        return view('integrated_games')->with([
            'title' => $title,
            'freeSpins' => $freeSpins,
            'gamesTypes' => $gamesTypes,
            'gamesCategories' => $gamesCategories,
            'titleDefault' => $appAdditional['defaultTitle'],
        ]);
    }

    public function getCategory(Request $request)
    {
    }

    public function getTypes()
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGames(Request $request)
    {
        $start = microtime(true);
        $configIntegratedGames = config('integratedGames.common');

        $whereGameList = [
            ['games_types_games.extra', '=', 1],
            ['games_list.active', '=', 1],
            ['games_types.active', '=', 1],
            ['games_categories.active', '=', 1],
        ];

        if ((int) $request->freeSpins === 1) {
            //to do
            $typeSlot = 10001;
            array_push($whereGameList, ['games_types_games.type_id', '=', $typeSlot]);
            array_push($whereGameList, ['games_list.free_round', '=', $request->freeSpins]);
        }

        if ((int) $request->categoryId !== 0) {
            array_push($whereGameList, ['games_list_extra.category_id', '=', $request->categoryId]);
        }

        if ((int) $request->typeId !== 0) {
            array_push($whereGameList, ['games_types_games.type_id', '=', $request->typeId]);
        }

        if ($request->search !== '') {
            array_push($whereGameList, ['games_list_extra.name', 'LIKE', '%'.$request->search.'%']);
        }

        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->params['settings'])->get()->pluck('value', 'code');
        $orderGames = ['games_list.rating', 'asc'];

        if (isset($settings['games'])) {
            //to do current field
            $orderGames = $definitionSettings[$settings['games']];
            $orderGames[0] = 'games_list.'.$orderGames[0];
        }

        //check this i use alien code
        if (Casino::isMobile()) {
            $paginationCount = $configIntegratedGames['listGames']['pagination']['mobile'];
            array_push($whereGameList, ['games_list.mobile', '=', 1]);
        } else {
            $paginationCount = $configIntegratedGames['listGames']['pagination']['desktop'];
            array_push($whereGameList, ['games_list.mobile', '=', 0]);
        }

        //check this query

        $codeCountry = GeneralHelper::visitorCountryCloudFlare();
        //for testing
        //$codeCountry = 'UA';

        $currentUser = $request->user();
        $emailsShowAllGames = config('appAdditional.emailsShowAllGames');

        if (! is_null($currentUser) and in_array($currentUser->email, $emailsShowAllGames)) {
            $gameList = DB::table('games_types_games')->select($this->relatedFields)
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->where($whereGameList)
                ->groupBy('games_types_games.game_id')
                ->orderBy($orderGames[0], $orderGames[1])->paginate($paginationCount);
        } else {
            array_push($this->relatedFields, 'rg_n.id as rg_n', 'rc_n.id as rc_n');
            array_push($this->relatedFields, 'rg.id as rg', 'rc.id as rc');

            $gameList = DB::table('games_types_games')->select($this->relatedFields)
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->leftJoin('restriction_games_by_country as rg_n', function ($join) use ($codeCountry) {
                    $join->on('rg_n.game_id', '=', 'games_list.id')
                        ->where('rg_n.code_country', '=', $codeCountry)
                        ->where('rg_n.mark', '=', 0);
                })
                ->leftJoin('restriction_categories_by_country as rc_n', function ($join) use ($codeCountry) {
                    $join->on('rc_n.category_id', '=', 'games_list_extra.category_id')
                        ->where('rc_n.code_country', '=', $codeCountry)
                        ->where('rc_n.mark', '=', 0);
                })
                ->leftJoin('restriction_games_by_country as rg', function ($join) use ($codeCountry) {
                    $join->on('rg.game_id', '=', 'games_list.id')
                        ->where('rg.mark', '=', 1);
                })
                ->leftJoin('restriction_categories_by_country as rc', function ($join) use ($codeCountry) {
                    $join->on('rc.category_id', '=', 'games_list_extra.category_id')
                        ->where('rc.mark', '=', 1);
                })
                ->where($whereGameList)
                ->whereRaw("(instr((select group_concat(code_country, '') from restriction_games_by_country".
                    " where game_id = games_list.id), '$codeCountry') OR rg.id is null) AND (rg_n.id is null)")
                ->whereRaw("(instr((select group_concat(code_country, '') from restriction_categories_by_country".
                    " where category_id = games_list_extra.category_id), '$codeCountry') OR rc.id is null) ".
                    "AND (IF(instr((select group_concat(code_country, '') from restriction_games_by_country where game_id = games_list.id ".
                    "and code_country = '$codeCountry'), '$codeCountry'), null, rc_n.id) is null)")
                ->groupBy('games_types_games.game_id')
                ->orderBy($orderGames[0], $orderGames[1])->paginate($paginationCount);
        }

        $viewMobile = (string) view('load.integrated_games_list_mobile')->with(['gameList' => $gameList]);
        $viewDesktop = (string) view('load.integrated_games_list_desktop')->with(['gameList' => $gameList]);

        return response()->json([
            'mobile' => $viewMobile,
            'desktop' => $viewDesktop,
            'time' => round(microtime(true) - $start, 4),
        ]);
    }

    public function getGameLink(Request $request)
    {
        //validate
        $configIntegratedGames = config('integratedGames.common');
        $providerId = $request->providerId;
        $gameId = $request->gameId;

        $validateParams = [
            'providerId' => $request->providerId,
            'gameId' => $request->gameId,
        ];

        $providers = $configIntegratedGames['providers'];
        $providerIds = array_map(function ($key, $value) {
            return $key;
        }, array_keys($providers), $providers);

        $validator = Validator::make($validateParams, [
            'gameId' => 'required|integer|exists:games_list,id',
            'providerId' => 'required|integer|in:'.implode(',', $providerIds),
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        //end validate

        $providerClass = new $providers[$providerId]['lib']();
        $linkRequest = $providerClass->loginPlayer($request);

        if ($linkRequest['success'] === true) {
            $link = $linkRequest['message']['gameLink'];
        } else {
            if (GeneralHelper::isTestMode()){
                dump($request);
                dd($linkRequest);
            }
            //throw exception
            throw new \Exception('Problem is by getting game link');
        }

        return view('load.integrated_games_link')->with(['link' => $link]);
    }
}
