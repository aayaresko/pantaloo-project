<?php

namespace App\Http\Controllers\Partner;

use DB;
use App\User;
use Carbon\Carbon;
use App\Transaction;
use App\Jobs\Withdraw;
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

    /**
     * GlobalAffiliatesController constructor.
     */
    public function __construct()
    {
        $this->fields = [
            0 => 'users.id',
            1 => 'users.email',
        ];

        $this->relatedFields = $this->fields;
        $this->relatedFields[2] = 'extra_users.base_line_cpa';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
        $param['currencyCode'] = config('app.currencyCode');

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

            foreach ($items as $user) {
                $result = collect();


                $userIds = User::where('users.agent_id', $user->id)
                    ->select('users.id')
                    ->distinct()
                    ->join('transactions as t', 't.user_id', '=', 'users.id')
                    ->where('t.type', 3)
                    ->pluck('id')->toArray();

                $transactionItems = Transaction::where($param['whereTransaction'])
                    ->whereIn('user_id', $userIds)->get();
                
                $cpumBtcLimit = is_null($user->base_line_cpa) ? $param['cpumBtcLimit'] : $user->base_line_cpa;
                $statistics = GeneralHelper::statistics($transactionItems, $cpumBtcLimit);
                $result->push($statistics);

                $user->pendingDeposits = $result->sum('pending_deposits') . ' ' . $param['currencyCode'];
                $user->confirmDeposits = $result->sum('confirm_deposits') . ' ' . $param['currencyCode'];
                $user->deposits = $result->sum('deposits') . ' ' . $param['currencyCode'];
                $user->revenue = $result->sum('revenue') . ' ' . $param['currencyCode'];
                $user->profit = $result->sum('profit') . ' ' . $param['currencyCode'];
                $user->bonus = $result->sum('bonus') . ' ' . $param['currencyCode'];
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

            foreach ($items as $user) {
                $result = collect();
                $userIds = User::where('users.agent_id', $user->id)
                    ->select('users.id')
                    ->distinct()
                    ->join('transactions as t', 't.user_id', '=', 'users.id')
                    ->where('t.type', 3)
                    ->pluck('id')->toArray();

                $transactionItems = Transaction::where($param['whereTransaction'])
                    ->whereIn('user_id', $userIds)->get();

                $cpumBtcLimit = is_null($user->base_line_cpa) ? $param['cpumBtcLimit'] : $user->base_line_cpa;
                $statistics = GeneralHelper::statistics($transactionItems, $cpumBtcLimit);
                $result->push($statistics);

                $user->pendingDeposits = $result->sum('pending_deposits') . ' ' . $param['currencyCode'];
                $user->confirmDeposits = $result->sum('confirm_deposits') . ' ' . $param['currencyCode'];
                $user->deposits = $result->sum('deposits') . ' ' . $param['currencyCode'];
                $user->revenue = $result->sum('revenue') . ' ' . $param['currencyCode'];
                $user->profit = $result->sum('profit') . ' ' . $param['currencyCode'];
                $user->bonus = $result->sum('bonus') . ' ' . $param['currencyCode'];
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

    public function withdraws(Request $request)
    {
        //to do pagination database and to do in for usual player
        //protection and validation for this page
        //get transaction for affiliates
        $frozen = Transaction::where('type', 4)
            ->whereRaw('user_id in (SELECT id FROM users WHERE role = 1)')
            ->where('withdraw_status', -1)->with('user')->get();

        $pending = Transaction::where('type', 4)
            ->whereRaw('user_id in (SELECT id FROM users WHERE role = 1)')
            ->where('withdraw_status', 0)->with('user')->get();

        $failed = Transaction::where('type', 4)
            ->whereRaw('user_id in (SELECT id FROM users WHERE role = 1)')
            ->where('withdraw_status', -2)->with('user')->get();

        $approved = Transaction::where('type', 4)
            ->whereRaw('user_id in (SELECT id FROM users WHERE role = 1)')
            ->where('withdraw_status', 1)->with('user')->get();

        $queue = Transaction::where('type', 4)
            ->whereRaw('user_id in (SELECT id FROM users WHERE role = 1)')
            ->where('withdraw_status', 3)->with('user')->get();

        return view('global-affiliates.withdraw', [
            'frozen' => $frozen,
            'pending' => $pending,
            'failed' => $failed,
            'approved' => $approved,
            'queue' => $queue
        ]);
    }

    public function approve(Transaction $transaction)
    {
        $user = User::where('id', $transaction->user_id)->first();

        if ((int)$user->role != 1) {
            return redirect()->back()->withErrors(['Something is wrong']);
        }

        if ($transaction->type == 4 and $transaction->withdraw_status == 0) {

            $transaction->withdraw_status = 3;
            $transaction->save();

            $this->dispatch(new Withdraw($transaction));

            return redirect()->route('globalAffiliates.withdraws')->with('msg', 'Transfer was complete!');
        } else {
            return redirect()->back()->withErrors(['Invalid type and status']);
        }
    }

    public function freeze(Transaction $transaction)
    {
        $user = User::where('id', $transaction->user_id)->first();

        if ((int)$user->role != 1) {
            return redirect()->back()->withErrors(['Something is wrong']);
        }

        if ($transaction->type == 4 and $transaction->withdraw_status == 0) {
            $transaction->withdraw_status = -1;
            $transaction->save();

            return redirect()->route('globalAffiliates.withdraws')->with('msg', 'Transaction was frozen');
        } else {
            return redirect()->back()->withErrors(['Invalid type']);
        }
    }

    public function unfreeze(Transaction $transaction)
    {
        $user = User::where('id', $transaction->user_id)->first();

        if ((int)$user->role != 1) {
            return redirect()->back()->withErrors(['Something is wrong']);
        }

        if ($transaction->type == 4 and $transaction->withdraw_status == -1) {
            $transaction->withdraw_status = 0;
            $transaction->save();

            return redirect()->route('globalAffiliates.withdraws')->with('msg', 'Transaction was unfrozen');
        } else {
            return redirect()->back()->withErrors(['Invalid type']);
        }
    }

    public function cancel(Transaction $transaction)
    {
        $user = User::where('id', $transaction->user_id)->first();

        if ((int)$user->role != 1) {
            return redirect()->back()->withErrors(['Something is wrong']);
        }

        if ($transaction->type == 4 and $transaction->withdraw_status == 3) {
            $transaction->withdraw_status = 0;
            $transaction->save();

            return redirect()->route('globalAffiliates.withdraws')->with('msg', 'Transaction was canceled');
        } else {
            return redirect()->back()->withErrors(['Invalid type']);
        }
    }
}
