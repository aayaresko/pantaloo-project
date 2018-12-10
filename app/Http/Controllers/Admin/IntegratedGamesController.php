<?php

namespace App\Http\Controllers\Admin;

use DB;
use Validator;
use App\Models\GamesList;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

/**
 * Class IntegratedGamesController
 * @package App\Http\Controllers\Admin
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
            1 => 'games_list.name',
            2 => 'games_list.provider_id',
            3 => 'games_list.type_id',
            4 => 'games_list.category_id',
            5 => 'games_list.image_filled',
            6 => 'games_list.rating',
            7 => 'games_list.active',
            8 => 'games_list.mobile',
            9 => 'games_list.created_at',
            10 => 'games_list.our_image',

        ];

        $this->relatedFields = $this->fields;
        $this->relatedFields[2] = 'games_list.provider_id as provider';
        $this->relatedFields[3] = 'games_types.name as type';
        $this->relatedFields[4] = 'games_categories.name as category';
        $this->relatedFields[5] = 'games_list.image_filled as image';
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
        return view('admin.integrated_games');
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

        $types = GamesType::select(['id', 'code', 'name'])->get();
        $categories = GamesCategory::select(['id', 'code', 'name'])->get();
        $game = GamesList::where([['games_list.id', '=', $request->id]])->select($this->fields)->first();
        return view('admin.integrated_game')->with([
            'game' => $game,
            'types' => $types,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gameUpdate(Request $request)
    {
        //to do validate image
        $this->validate($request, [
            'name' => 'string|min:3|max:100',
            'type_id' => 'integer|exists:games_types,id',
            'category_id' => 'integer|exists:games_categories,id',
            'rating' => 'integer',
            'image' => 'image|max:1000|mimes:jpeg,png',//to do this to config file if will DRY
        ]);

        DB::beginTransaction();
        try {
            $game = GamesList::where('id', $request->id)->first();
            $updatedGame = $request->toArray();
            if ($request->hasFile('image')) {
                $image = $request->image;
                $nameImage = $request->id . '.' . $image->getClientOriginalExtension();
                $pathImage = "/gamesPictures/{$nameImage}";
                Storage::put('public' . $pathImage, file_get_contents($image->getRealPath()));
                $updatedGame['our_image'] = '/storage' . $pathImage;
                unset($updatedGame['image']);
            }

            $active = $request->input('active');
            if (!is_null($active)) {
                $updatedGame['active'] = ($active === 'on') ? 1 : 0;
            }

            $mobile = $request->input('mobile');
            if (!is_null($active)) {
                $updatedGame['mobile'] = ($mobile === 'on') ? 1 : 0;
            }

            unset($updatedGame['_token']);
            $default_provider_image = $request->input('default_provider_image');

            if (!is_null($default_provider_image)) {
                if ($default_provider_image === 'on') {
                    $updatedGame['our_image'] = $game->image_filled;
                    unset($updatedGame['default_provider_image']);
                }
            }
            GamesList::where('id', $request->id)->update($updatedGame);
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
        /* COLUMNS */
        $param['columns'] = $this->fields;
        $param['columnsAlias'] = $this->relatedFields;
        /* COLUMNS */

        return $param;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $param = $this->preparationParams($request);
        /* ACT */
        $countSum = GamesList::select(array(
            DB::raw('COUNT(*) as `count`')))
            ->where([])->get()->toArray();

        $totalData = $countSum[0]['count'];
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $order = $param['columns'][$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            /* SORT */

            $items = GamesList::leftJoin('games_types',
                'games_types.id', '=', 'games_list.type_id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list.category_id')
                ->where([])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select($param['columnsAlias'])->get();
        } else {
            /* SEARCH */
            $search = $request->input('search.value');

            $items = GamesList::leftJoin('games_types',
                'games_types.id', '=', 'games_list.type_id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list.category_id')
                ->where([
                    [$param['columns'][0], 'LIKE', "%{$search}%"],
                ])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select($param['columnsAlias'])->get();

            $countSum = GamesList::select(array(
                DB::raw('COUNT(*) as `count`')))
                ->leftJoin('games_types',
                    'games_types.id', '=', 'games_list.type_id')
                ->leftJoin('games_categories', 'games_categories.id', '=', 'games_list.category_id')
                ->where([
                    [$param['columns'][0], 'LIKE', "%{$search}%"],
                ])
                ->get()->toArray();

            $totalFiltered = $countSum[0]['count'];
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
            $image = is_null($item->our_image) ? $item->image : $item->our_image;
            $item->image = view('admin.parts.imageTable', ['image' => $image])->render();
            $item->mobile = view('admin.parts.switch', ['switch' => $item->mobile])->render();
            $item->active = view('admin.parts.switch', ['switch' => $item->active])->render();

            return $item;
        });

        $jsonData = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return response()->json($jsonData);
    }
}
