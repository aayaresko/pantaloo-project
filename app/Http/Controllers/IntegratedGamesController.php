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
    protected $params = [];

    /**
     * IntegratedGamesController constructor.
     */
    public function __construct()
    {
        $this->params['settings'] = ['id', 'code', 'value'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $dummyPicture = $configIntegratedGames['dummyPicture'];
        View::share('dummyPicture', $dummyPicture);
        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->params['settings'])->get()->pluck('value', 'code');

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
            'gamesTypes' => $gamesTypes,
            'gamesCategories' => $gamesCategories,
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
            ['active', '=', 1],
        ];

        if ((int)$request->categoryId !== 0) {
            array_push($whereGameList, ['category_id', '=', $request->categoryId]);
        }

        if ((int)$request->typeId !== 0) {
            array_push($whereGameList, ['type_id', '=', $request->typeId]);
        }

        if ($request->search !== '') {
            array_push($whereGameList, ['our_name', 'LIKE', '%' . $request->search . '%']);
        }

        $definitionSettings = $configIntegratedGames['listSettings'];
        $settings = GamesListSettings::select($this->params['settings'])->get()->pluck('value', 'code');
        $orderGames = ['rating', 'asc'];
        if (isset($settings['games'])) {
            $orderGames = $definitionSettings[$settings['games']];
        }

        //check this i use alien code
        if (Casino::isMobile()) {
            $paginationCount = $configIntegratedGames['listGames']['pagination']['mobile'];
            array_push($whereGameList, ['mobile', '=', 1]);
        } else {
            $paginationCount = $configIntegratedGames['listGames']['pagination']['desktop'];
            array_push($whereGameList, ['mobile', '=', 0]);
        }

        $gameList = GamesList::where($whereGameList)->orderBy($orderGames[0], $orderGames[1])->paginate($paginationCount);

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
