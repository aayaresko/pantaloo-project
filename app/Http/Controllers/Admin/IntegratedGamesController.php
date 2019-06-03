<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Country;
use App\CustomField;
use App\Models\GamesList;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Models\GamesTypeGame;
use App\Models\GamesListExtra;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\RestrictionGamesCountry;
use Illuminate\Support\Facades\Storage;

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
     * IntegratedGamesController constructor.
     */
    public function __construct()
    {
        $this->fields = [
            0 => 'games_list.id',
            1 => 'games_list_extra.name',
            2 => 'games_list.provider_id',

            4 => 'games_list_extra.category_id',
            5 => 'games_list_extra.image',
            6 => 'games_list.rating',
            7 => 'games_list.active',
            8 => 'games_list.mobile',
            9 => 'games_list.created_at',
        ];

        $this->relatedFields = $this->fields;
        $this->relatedFields[2] = 'games_list.provider_id as provider';
        $this->relatedFields[3] = DB::raw('group_concat(games_types.name) as type');
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
        $dummyPicture = $configIntegratedGames['dummyPicture'];
        $types = GamesType::select(['id', 'code', 'name'])->get()->all();
        View::share('dummyPicture', $dummyPicture);

        return view('admin.integrated_games')
            ->with(['types' => $types]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function game(Request $request)
    {
        $configIntegratedGames = config('integratedGames.common');
        $dummyPicture = $configIntegratedGames['dummyPicture'];
        View::share('dummyPicture', $dummyPicture);

        $adminConfig = config('adminPanel');
        $imageConfig = $adminConfig['image'];
        View::share('maxSizeImage', $imageConfig['maxSize']);
        View::share('typesImage', $imageConfig['mimes']);

        $types = GamesType::select(['id', 'code', 'name'])->get()->all();
        $categories = GamesCategory::select(['id', 'code', 'name'])->get()->all();
        //to do check this in one query - i don't have time
        $gameId = $request->id;
        $whereCompare = [
            ['games_list.id', '=', $gameId],
            ['games_types_games.extra', '=', 1],
        ];

        $whereCompareDefault = [
            ['games_list.id', '=', $gameId],
            ['games_types_games.extra', '=', 0],
        ];

        $addFields = [
            10 => 'games_list.name as default_name',
            13 => 'games_list.category_id as default_category_id',
            14 => 'games_list.our_image as default_image',
            3 => DB::raw('group_concat(games_types_games.type_id) as type'),
        ];
        $fields = array_merge_recursive($addFields, $this->fields);

        //to do optimize this ! sometimes
        $game = GamesTypeGame::select($fields)
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->where($whereCompare)
            ->groupBy('games_types_games.game_id')
            ->first();

        $gameDefault = GamesTypeGame::select([$fields[3]])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->where($whereCompareDefault)
            ->groupBy('games_types_games.game_id')
            ->first();

        $game->default_type_id = $gameDefault->type;
        $game->type_id = explode(',', $game->type);
        $game->default_type_id = explode(',', $game->default_type_id);

        //work with country - to one request
        $countries = Country::all();
        $markConfig = config('appAdditional.restrictionMark');
        $allowGameCountry = RestrictionGamesCountry::where('game_id', $gameId)
            ->where('mark', $markConfig['enable'])
            ->pluck('code_country')->toArray();

        $banGameCountry = RestrictionGamesCountry::where('game_id', $gameId)
            ->where('mark', $markConfig['disable'])
            ->pluck('code_country')->toArray();
        $gameCountries = [
            'allow' => $allowGameCountry,
            'ban' => $banGameCountry,
        ];

        return view('admin.integrated_game')->with([
            'game' => $game,
            'types' => $types,
            'countries' => $countries,
            'categories' => $categories,
            'gameCountries' => $gameCountries,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gameUpdate(Request $request)
    {
        //dd($request->toArray());
        $adminConfig = config('adminPanel');
        $imageConfig = $adminConfig['image'];
        $date = new \DateTime();

        $this->validate($request, [
            'name' => 'string|min:3|max:100',
            'type_id' => 'array',
            'type_id.*' => 'exists:games_types,id', // check each item in the array
            'allowCountryGames_codes.*' => 'exists:countries,code',
            'banCountryGames_codes.*' => 'exists:countries,code',
            'category_id' => 'integer|exists:games_categories,id',
            'rating' => 'integer',
            'image' => "image|max:{$imageConfig['maxSize']}|mimes:".implode(',', $imageConfig['mimes']),
        ]);

        DB::beginTransaction();

        try {
            $game = GamesList::where('id', $request->id)->first();
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id.'.'.$image->getClientOriginalExtension();
                $pathImage = "/gamesPictures/{$nameImage}";
                Storage::put('public'.$pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['image'] = '/storage'.$pathImage;
            }

            $active = $request->input('active');
            if (! is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            } else {
                $updatedGame['active'] = 0;
            }

            unset($updatedGame['_token']);
            $default_provider_image = $request->input('default_provider_image');

            if (! is_null($default_provider_image)) {
                if ($default_provider_image === 'on') {
                    $updatedGame['image'] = $game->our_image;
                    unset($updatedGame['default_provider_image']);
                }
            }

            GamesList::where('id', $request->id)->update([
                'rating' => $updatedGame['rating'],
                'active' => $updatedGame['active'],
            ]);
            unset($updatedGame['rating']);
            unset($updatedGame['active']);
            unset($updatedGame['type_id']);
            unset($updatedGame['banCountryGames_codes']);
            unset($updatedGame['allowCountryGames_codes']);

            GamesListExtra::where('game_id', $request->id)->update($updatedGame);

            //update type
            if ($request->has('type_id')) {
                $typeIds = $request->type_id;
                $relationType = [];
                foreach ($typeIds as $typeId) {
                    array_push($relationType, [
                        'extra' => 1,
                        'game_id' => $request->id,
                        'type_id' => $typeId,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }

                GamesTypeGame::where([
                    ['extra', '=', 1],
                    ['game_id', '=', $request->id],
                ])->delete();

                GamesTypeGame::insert($relationType);
            }

            /* work with restriction */
            $gameId = $request->id;
            $markConfig = config('appAdditional.restrictionMark');
            $currentDate = new \DateTime();
            //ALLOW
            if ($request->has('allowCountryGames_codes')) {
                $restrictionAllowItems = [];

                RestrictionGamesCountry::where('game_id', $gameId)
                    ->where('mark', $markConfig['enable'])->delete();

                foreach ($request['allowCountryGames_codes'] as $allowCode) {
                    array_push($restrictionAllowItems, [
                        'game_id' => $gameId,
                        'code_country' => $allowCode,
                        'mark' => $markConfig['enable'],
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
                RestrictionGamesCountry::insert($restrictionAllowItems);
            } else {
                //clear
                RestrictionGamesCountry::where('game_id', $gameId)
                    ->where('mark', $markConfig['enable'])->delete();
            }

            //BAN
            if ($request->has('banCountryGames_codes')) {
                $restrictionBanItems = [];

                RestrictionGamesCountry::where('game_id', $gameId)
                    ->where('mark', $markConfig['disable'])->delete();

                foreach ($request['banCountryGames_codes'] as $banCode) {
                    array_push($restrictionBanItems, [
                        'game_id' => $gameId,
                        'code_country' => $banCode,
                        'mark' => $markConfig['disable'],
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
                RestrictionGamesCountry::insert($restrictionBanItems);
            } else {
                //clear
                RestrictionGamesCountry::where('game_id', $gameId)
                    ->where('mark', $markConfig['disable'])->delete();
            }
            /* end work with restriction */
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        DB::commit();

        return redirect()->route('admin.integratedGame', $game->id)->with('msg', 'Game was edited');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function preparationParams(Request $request)
    {
        $param['columns'] = $this->fields;
        $param['columnsAlias'] = $this->relatedFields;
        $param['whereCompare'] = [
            ['games_types_games.extra', '=', 1],
        ];

        if ($request->has('type_id')) {
            if ($request->type_id > 0) {
                array_push($param['whereCompare'],
                    ['games_types_games.type_id', '=', $request->type_id]);
            }

            if ($request->type_id == -1) {
                $firstLoad = CustomField::where('code', 'get_games')->first();
                if (! is_null($firstLoad)) {
                    array_push($param['whereCompare'],
                        ['games_list.created_at', '>', $firstLoad->created_at]);

                    array_push($param['whereCompare'],
                        ['games_list.created_at', '=', DB::raw('games_list.updated_at')]);
                }
            }
        }

        return $param;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $start1 = microtime(true);
        $param = $this->preparationParams($request);
        /* ACT */
        $whereCompare = $param['whereCompare'];

        $countSum = GamesTypeGame::select(['games_types_games.game_id', DB::raw('COUNT(*) as `count`')])
            ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
            ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
            ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
            ->where($whereCompare)
            ->groupBy('games_types_games.game_id')
            ->get()->toArray();

        $totalData = count($countSum);
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $order = $param['columns'][$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            /* SORT */
            $items = GamesTypeGame::select([DB::raw('COUNT(*) as `count`')])
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->where($whereCompare)
                ->groupBy('games_types_games.game_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select($param['columnsAlias'])->get()->all();
        } else {
            /* SEARCH */
            $search = $request->input('search.value');

            if (is_numeric($search)) {
                array_push($whereCompare, [$param['columns'][0], 'LIKE', "%{$search}%"]);
            } else {
                array_push($whereCompare, [$param['columns'][1], 'LIKE', "%{$search}%"]);
            }

            $items = GamesTypeGame::select([DB::raw('COUNT(*) as `count`')])
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->where($whereCompare)
                ->groupBy('games_types_games.game_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select($param['columnsAlias'])->get()->all();

            $countSum = GamesTypeGame::select([DB::raw('COUNT(*) as `count`')])
                ->leftJoin('games_list', 'games_types_games.game_id', '=', 'games_list.id')
                ->leftJoin('games_list_extra', 'games_list.id', '=', 'games_list_extra.game_id')
                ->leftJoin('games_types', 'games_types_games.type_id', '=', 'games_types.id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list_extra.category_id')
                ->where($whereCompare)
                ->groupBy('games_types_games.game_id')
                ->get()->toArray();

            $totalFiltered = count($countSum);
        }
        /* END */

        /* TO VIEW */
        $data = $items;
        $configIntegratedGames = config('integratedGames.common');
        $param['providers'] = $configIntegratedGames['providers'];

        $data->map(function ($item, $key) use ($param) {
            $idProvider = $item->provider;
            $item->provider = $param['providers'][$idProvider]['code'];
            $item->edit = view('admin.parts.buttons', ['id' => $item->id])->render();
            $item->image = view('admin.parts.imageTable', ['image' => $item->image])->render();
            $item->mobile = view('admin.parts.switch', ['switch' => $item->mobile])->render();
            $item->active = view('admin.parts.switch', ['switch' => $item->active])->render();

            return $item;
        });

        $jsonData = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
            'time' => round(microtime(true) - $start1, 4),
        ];

        return response()->json($jsonData);
    }
}
