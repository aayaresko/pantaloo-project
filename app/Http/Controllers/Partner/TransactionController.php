<?php

namespace App\Http\Controllers\Partner;

use DB;
use App\User;
use Validator;
use App\Transaction;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
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
            0 => 'users.id',
            1 => 'transactions.created_at',
            2 => 'transactions.type',
            3 => 'transactions.sum',
            4 => 'transactions.bonus_sum',
            5 => 'transactions.id',
        ];

        $this->relatedFields = $this->fields;
        $this->relatedFields[0] = 'users.email as email';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $commonConfig = config('integratedGames.common');
        $typeTransactionsObject = $commonConfig['typeTransaction'];
        $typeTransactions = json_decode(json_encode($typeTransactionsObject), false);
        $users = User::where('agent_id', Auth::user()->id)->get();
        $gamesTypes = GamesType::select(['id', 'name', 'active', 'rating'])
            ->where([['active', '=', 1]])->orderBy('id')->get();
        $gamesCategories = GamesCategory::select(['id', 'name', 'active', 'rating'])
            ->where([['active', '=', 1]])->orderBy('id')->get();
        return view('affiliates.transactions',
            [
                'users' => $users,
                'gamesTypes' => $gamesTypes,
                'types' => $typeTransactions,
                'gamesCategories' => $gamesCategories,
            ]);
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
        $param['user'] = $request->user();
        $param['conditions'] = [
            ['transactions.agent_id', '=', $param['user']->id]
        ];
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
        $countSum = Transaction::select([DB::raw('COUNT(*) as `count`')])
            ->leftJoin('games_pantallo_transactions',
                'transactions.id', '=', 'games_pantallo_transactions.transaction_id')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->leftJoin('games_list', 'games_pantallo_transactions.game_id', '=', 'games_list.id')
            ->leftJoin('games_types', 'games_list.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_list.category_id', '=', 'games_categories.id')
            ->where($param['conditions'])->get()->toArray();

        $totalData = $countSum[0]['count'];
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $order = $param['columns'][$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $items = Transaction::leftJoin('games_pantallo_transactions',
            'transactions.id', '=', 'games_pantallo_transactions.transaction_id')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->leftJoin('games_list', 'games_pantallo_transactions.game_id', '=', 'games_list.id')
            ->leftJoin('games_types', 'games_list.type_id', '=', 'games_types.id')
            ->leftJoin('games_categories', 'games_list.category_id', '=', 'games_categories.id')
            ->where($param['conditions'])
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->select($param['columnsAlias'])->get();
        /* END */

        /* TO VIEW */
        $data = $items;
        $param['currencyCode'] = config('app.currencyCode');

        $data->map(function ($item, $key) use ($param) {
//            $item->image = view('admin.parts.imageTable', ['image' => $image])->render();
//            $item->mobile = view('admin.parts.switch', ['switch' => $item->mobile])->render();
//            $item->active = view('admin.parts.switch', ['switch' => $item->active])->render();
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
