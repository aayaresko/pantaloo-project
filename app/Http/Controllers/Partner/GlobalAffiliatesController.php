<?php

namespace App\Http\Controllers\Partner;

use DB;
use App\User;
use Carbon\Carbon;
use App\Transaction;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AffiliatesController
 * @package App\Http\Controllers\Partner
 */
class GlobalAffiliatesController extends Controller
{
    /**
     * @var array
     */
    protected $fields;
    /**
     * @var array
     */
    protected $relatedFields;

    public function __construct()
    {
        $this->fields = [
            0 => 'users.id',
            1 => 'users.email',
        ];

        $this->relatedFields = $this->fields;
        $this->relatedFields[2] = 'extra_users.base_line_cpa';
    }

    public function index()
    {
        //get view
        return view('global-affiliates.finance');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function preparationParams(Request $request)
    {
        $param['columns'] = $this->fields;
        $param['columnsAlias'] = $this->relatedFields;
        //date
        $dateStart = new Carbon();
        $dateStart->setTimestamp($request->dateStart);
        $endStart = new Carbon();
        $endStart->setTimestamp($request->endStart);

        $param['whereTransaction'] = [
            ['transactions.created_at', '>=', $dateStart],
            ['transactions.created_at', '<=', $endStart],
        ];

        $param['whereUsers'] = [];

        $param['dateStart'] = $dateStart;
        $param['endStart'] = $endStart;

        $param['cpumBtcLimit'] = config('appAdditional.defaultmBtcCpu');

        return $param;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFinance(Request $request)
    {
        $start1 = microtime(true);
        $param = $this->preparationParams($request);
        /* ACT */

        $countSum = User::select([DB::raw('COUNT(*) as `count`')])
            ->whereRaw('id in (SELECT id FROM users WHERE role = 1)')
            ->where($param['whereUsers'])
            ->get()->toArray();

        $totalData = $countSum[0]['count'];
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');

        $order = $param['columns'][$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            /* SORT */

            $items = User::leftJoin('extra_users', 'users.id', '=', 'extra_users.user_id')
                ->whereRaw('users.id in (SELECT id FROM users WHERE role = 1)')
                ->where($param['whereUsers'])
                ->select(['users.id', 'users.email', 'extra_users.base_line_cpa'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $result = collect();
            foreach ($items as $user) {
                $transactionItems = Transaction::where($param['whereTransaction'])
                    ->where('user_id', $user->id)->get();
                $cpumBtcLimit = is_null($user->base_line_cpa) ? $param['cpumBtcLimit'] : $user->base_line_cpa;

                $statistics = GeneralHelper::statistics($transactionItems, $cpumBtcLimit);
                $result->push($statistics);
                $user->deposits = $result->sum('deposits');
                $user->revenue = $result->sum('revenue');
                $user->profit = $result->sum('profit');
                $user->bonus = $result->sum('bonus');
                $user->cpa = $result->sum('cpa');
            }
        } else {
            /* SEARCH */
            $search = $request->input('search.value');

            if (is_numeric($search)) {
                array_push($param['whereUsers'], [$param['columns'][0], 'LIKE', "%{$search}%"]);
            } else {
                array_push($param['whereUsers'], [$param['columns'][1], 'LIKE', "%{$search}%"]);
            }

            $items = User::leftJoin('extra_users', 'users.id', '=', 'extra_users.user_id')
                ->whereRaw('users.id in (SELECT id FROM users WHERE role = 1)')
                ->where($param['whereUsers'])
                ->select(['users.id', 'users.email', 'extra_users.base_line_cpa'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $result = collect();
            foreach ($items as $user) {
                $transactionItems = Transaction::where($param['whereTransaction'])
                    ->where('user_id', $user->id)->get();
                $cpumBtcLimit = is_null($user->base_line_cpa) ? $param['cpumBtcLimit'] : $user->base_line_cpa;
                $statistics = GeneralHelper::statistics($transactionItems, $cpumBtcLimit);
                $result->push($statistics);
                $user->deposits = $result->sum('deposits');
                $user->revenue = $result->sum('revenue');
                $user->profit = $result->sum('profit');
                $user->bonus = $result->sum('bonus');
                $user->cpa = $result->sum('cpa');
            }

            $countSum = User::select([DB::raw('COUNT(*) as `count`')])
                ->whereRaw('id in (SELECT id FROM users WHERE role = 1)')
                ->where($param['whereUsers'])
                ->get()->toArray();

            $totalFiltered = $countSum[0]['count'];
        }
        /* END */

        /* TO VIEW */
        $data = $items;

        $jsonData = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            'time' => round(microtime(true) - $start1, 4)
        );

        return response()->json($jsonData);
    }

}
