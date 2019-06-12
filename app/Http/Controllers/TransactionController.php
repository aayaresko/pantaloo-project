<?php

namespace App\Http\Controllers;

use App\User;
use App\Transaction;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $prefix = $request->route()->getPrefix();

        if ($prefix == '/admin') {
            $is_admin = true;
        } else {
            $is_admin = false;
        }

        if ($is_admin and ! Auth::user()->isAdmin()) {
            throw new \Exception('Error');
        }

        if ($is_admin) {
            $users = User::all();
        } else {
            $users = User::where('agent_id', Auth::user()->id)->get()->all();
        }

        return view('agent.transactions', ['users' => $users, 'is_admin' => $is_admin]);
    }

    public function filter(Request $request)
    {
        $prefix = $request->route()->getPrefix();

        if ($prefix == '/admin') {
            $is_admin = true;
        } else {
            $is_admin = false;
        }

        if ($is_admin and ! Auth::user()->isAdmin()) {
            throw new \Exception('Error');
        }

        if ($request->input('iDisplayStart')) {
            $start = $request->input('iDisplayStart');
        } else {
            $start = 0;
        }

        if ($request->input('iDisplayLength')) {
            $length = $request->input('iDisplayLength');
        } else {
            $length = 10;
        }

        if ($is_admin) {
            $transactions = Transaction::orderBy('id', 'DESC');
        } else {
            $transactions = Transaction::where('agent_id', Auth::user()->id);
        }

        $result = [
            'draw' => 0,
            'recordsTotal' => $transactions->count(),
            'recordsFiltered' => $transactions->count(),
            'data' => [],
        ];

        if ($request->input('user_id') and $request->input('user_id') != 0) {
            $transactions = $transactions->where('user_id', $request->input('user_id'));
        }

        if ($request->input('category_id') and $request->input('category_id') != 0) {
            $category_id = $request->input('category_id');

            $transactions = $transactions->whereHas('token', function ($q) use ($category_id) {
                $q->whereHas('slot', function ($q) use ($category_id) {
                    $q->whereHas('category', function ($q) use ($category_id) {
                        $q->where('id', $category_id);
                    });
                });
            });
        }

        if ($request->input('type_id') and $request->input('type_id') != 0) {
            if ($request->input('type_id') == -1) {
                $transactions = $transactions->whereIn('type', [1, 2]);
            } else {
                $transactions = $transactions->where('type', $request->input('type_id'));
            }
        }

        $result['recordsFiltered'] = $transactions->count();

        $transactions = $transactions->offset($start)->limit($length);

        $transactions = $transactions->orderBy('created_at', 'DESC');

        $transactions = $transactions->get()->all();

        foreach ($transactions as $transaction) {
            $result['data'][] = [
                $transaction->user->email,
                $is_admin ? $transaction->created_at->tz("Europe/Kiev")->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i'),
                $transaction->getDescription(),
                $transaction->getAmount(),
                $transaction->getBonusAmount(),
            ];
        }

        return response()->json($result);
    }
}
