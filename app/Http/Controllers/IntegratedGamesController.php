<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Validator;
use App\Slots\Casino;
use App\Models\GamesList;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Models\GamesListSettings;
use Illuminate\Support\Facades\View;

/**
 * Class IntegratedGamesController
 * @package App\Http\Controllers
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
    public function index(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $appAdditional =  config('appAdditional');
        $dummyPicture = $configIntegratedGames['dummyPicture'];
        $defaultTypes = $appAdditional['defaultTypes'];
        $defaultTitle = $appAdditional['defaultTitle'];

        View::share('dummyPicture', $dummyPicture);
        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->params['settings'])->get()->pluck('value', 'code');

        $title = $defaultTitle;
        if ($request->has('type_id')) {
            foreach ($defaultTypes as $defaultType) {
                if ($defaultType['id'] == $request->type_id) {
                    $title = $defaultType['name'];
                }
            }
        }

        $orderType = ['rating', 'desc'];
        if (isset($settings['types'])) {
            $orderType = $definitionSettings[$settings['types']];
        }

        $orderCategoty = ['rating', 'desc'];
        if (isset($settings['types'])) {
            $orderCategoty = $definitionSettings[$settings['categories']];
        }

        $gamesTypes = GamesType::where([
            ['active', '=', 1],
        ])->orderBy($orderType[0], $orderType[1])->get();

        $gamesCategories = GamesCategory::where([
            ['active', '=', 1],
        ])->orderBy($orderCategoty[0], $orderCategoty[1])->get();

        return view('integrated_games')->with([
            'title' => $title,
            'gamesTypes' => $gamesTypes,
            'gamesCategories' => $gamesCategories,
            'titleDefault' => $appAdditional['defaultTitle'],
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGames(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');

        $whereGameList = [
            ['games_types_games.extra', '=', 1],
            ['games_list.active', '=', 1],
            ['games_types.active', '=', 1],
            ['games_categories.active', '=', 1],
        ];

        if ((int)$request->categoryId !== 0) {
            array_push($whereGameList, ['games_list_extra.category_id', '=', $request->categoryId]);
        }

        if ((int)$request->typeId !== 0) {
            array_push($whereGameList, ['games_types_games.type_id', '=', $request->typeId]);
        }

        if ($request->search !== '') {
            array_push($whereGameList, ['games_list_extra.name', 'LIKE', '%' . $request->search . '%']);
        }

        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->params['settings'])->get()->pluck('value', 'code');
        $orderGames = ['games_list.rating', 'asc'];

        if (isset($settings['games'])) {
            //to do current field
            $orderGames = $definitionSettings[$settings['games']];
            $orderGames[0] = 'games_list.' . $orderGames[0];
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

        $gameList = DB::table('games_types_games')->select($this->relatedFields)
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where($whereGameList)
            ->groupBy('games_types_games.game_id')
            ->orderBy($orderGames[0], $orderGames[1])->paginate($paginationCount);

        $viewMobile = (string)view('load.integrated_games_list_mobile')->with(['gameList' => $gameList]);
        $viewDesktop = (string)view('load.integrated_games_list_desktop')->with(['gameList' => $gameList]);

        return response()->json([
            'mobile' => $viewMobile,
            'desktop' => $viewDesktop
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
            'providerId' => 'required|integer|in:' . implode(',', $providerIds),
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        //end validate

        $providerClass = new $providers[$providerId]['lib']();
        $link = $providerClass->loginPlayer($request);

        if ($link['success'] === true) {
            $link = $link['message']['gameLink'];
        }
        return view('load.integrated_games_link')->with(['link' => $link]);
    }
}
