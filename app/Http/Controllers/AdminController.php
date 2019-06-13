<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Transaction;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $month_total = Transaction::whereIn('type', [1, 2])->where('created_at', '>=', Carbon::now()->firstOfMonth())->sum('sum');
        $last_month_total = Transaction::whereIn('type', [1, 2])->where('created_at', '>=', Carbon::now()->modify('-1 month')->firstOfMonth())->where('created_at', '<=', Carbon::now()->modify('-1 month'))->sum('sum');
        $month_procent = self::calculate(-1 * $month_total, -1 * $last_month_total);
        $month_length = Carbon::now()->format('d') / Carbon::now()->modify('last day of this month')->format('d');

        $today_total = Transaction::whereIn('type', [1, 2])->where('created_at', '>=', Carbon::now()->setTime(0, 0, 0))->sum('sum');
        $last_today_total = Transaction::whereIn('type', [1, 2])->where('created_at', '>=', Carbon::now()->modify('-1 day')->setTime(0, 0, 0))->where('created_at', '<=', Carbon::now()->modify('-1 day'))->sum('sum');
        $today_procent = self::calculate(-1 * $today_total, -1 * $last_today_total);
        $today_length = Carbon::now()->format('H') / 24;

        $pending_money = Transaction::where('type', 4)->where('withdraw_status', 0)->sum('sum');
        $frozen_money = Transaction::where('type', 4)->where('withdraw_status', -1)->sum('sum');
        $users_online = User::where('last_activity', '>=', Carbon::now()->modify('-1 minute'))->count();

        $today_users = User::where('created_at', '>=', Carbon::now()->setTime(0, 0, 0))->count();
        $last_today_users = User::where('created_at', '>=', Carbon::now()->modify('-1 day')->setTime(0, 0, 0))->where('created_at', '<=', Carbon::now()->modify('-1 day'))->count();
        $users_procent = self::calculate($today_users, $last_today_users);

        $total_users = User::count();

        try {
            $from = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        } catch (\Exception $e) {
            $from = Carbon::now();
        }

        try {
            $to = Carbon::createFromFormat('Y-m-d', $request->input('end'));
        } catch (\Exception $e) {
            $to = Carbon::now();
        }

        $to->setTime(23, 59, 59);
        $from->setTime(0, 0, 0);

        $transactions = collect();

        $users = User::where('role', 0)->get();

        $result = collect();

        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            //$stat['profit'] = $stat['revenue'] * Auth::user()->commission / 100;

            foreach ($stat as $key => $value) {
                $stat[$key] = round($value, 2);
            }

            $stat['user'] = $user;

            $result->push($stat);
        }

        return view('admin.dashboard', [
            'month_total' => $month_total,
            'month_procent' => $month_procent,
            'month_length' => $month_length,
            'today_total' => $today_total,
            'today_procent' => $today_procent,
            'today_length' => $today_length,
            'pending_money' => $pending_money,
            'frozen_money' => $frozen_money,
            'users_online' => $users_online,
            'today_users' => $today_users,
            'total_users' => $total_users,
            'users_procent' => $users_procent,
            'users' => $result,
            'deposit_total' => $result->sum('deposits'),
            'bonus_total' => $result->sum('bonus'),
            'revenue_total' => $result->sum('revenue'),
            'profit_total' => $result->sum('adminProfit'),
        ]);
    }

    public function balance(Request $request)
    {
        try {
            $from = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        } catch (\Exception $e) {
            $from = Carbon::now();
        }

        try {
            $to = Carbon::createFromFormat('Y-m-d', $request->input('end'));
        } catch (\Exception $e) {
            $to = Carbon::now();
        }

        $to->setTime(23, 59, 59);
        $from->setTime(0, 0, 0);

        $transactions = collect();

        $users = User::where('role', 0)->get();

        $result = collect();

        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            //$stat['profit'] = $stat['revenue'] * Auth::user()->commission / 100;

            foreach ($stat as $key => $value) {
                $stat[$key] = round($value, 2);
            }

            $stat['user'] = $user;

            $result->push($stat);
        }

        $currencyCode = config('app.currencyCode');

        return view('admin.balance', [
            'users' => $result,
            'deposit_total' => $result->sum('deposits'),
            'bonus_total' => $result->sum('bonus'),
            'revenue_total' => $result->sum('revenue'),
            'profit_total' => $result->sum('adminProfit'),
            'currencyCode' => $currencyCode,
        ]);
    }

    public static function calculate($num_1, $num_2)
    {
        if ($num_1 == 0 and $num_2 == 0) {
            return 0;
        }

        if ($num_1 > $num_2) {
            if ($num_2 == 0) {
                return 1;
            }

            return $num_1 / $num_2;
        } elseif ($num_2 > $num_1) {
            if ($num_1 == 0) {
                return -1;
            }

            return -1 * ($num_2 / $num_1);
        } else {
            return 0;
        }
    }
}
