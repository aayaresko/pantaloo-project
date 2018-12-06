<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\GamesList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IntegratedGamesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.integrated_games');
    }

    protected function preparationParams(Request $request)
    {
        /* COLUMNS */
        $param['columns'] = [
            0 => 'games_list.id',
            1 => 'games_list.name',
            2 => 'games_list.provider_id',
            3 => 'games_list.type_id',
            4 => 'games_list.category_id',
            5 => 'games_list.image_filled',
            6 => 'games_list.active',
            7 => 'games_list.mobile',
            8 => 'games_list.rating',
            9 => 'games_list.created_at',

        ];
        $param['columnsAlias'] = $param['columns'];
        $param['columnsAlias'][2] = 'games_list.provider_id as provider';
        $param['columnsAlias'][3] = 'games_types.name as type';
        $param['columnsAlias'][4] = 'games_categories.name as category';
        $param['columnsAlias'][5] = 'games_list.image_filled as image';
        /* COLUMNS */

        return $param;
    }

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
            $item->image = view('admin.parts.imageTable', ['image' => $item->image])->render();
            $item->rating = view('admin.parts.switch', ['switch' => $item->rating])->render();
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
