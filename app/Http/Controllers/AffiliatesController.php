<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Payment;
use App\Tracker;
use Carbon\Carbon;
use App\Transaction;
use App\Bitcoin\Service;
use App\Models\GamesType;
use Illuminate\Http\Request;
use App\Models\GamesCategory;
use App\Models\Partners\Feedback;
use Illuminate\Support\Facades\Auth;

class AffiliatesController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        //test to two auth
        if (Auth::check()) {
            if (Auth::user()->isAgent()) {
                return redirect()->route('agent.dashboard');
            }
        }
        return view('affiliates.lending');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transaction()
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

    public function transactionFilter(Request $request)
    {


        if ($request->input('iDisplayStart')) $start = $request->input('iDisplayStart');
        else $start = 0;

        if ($request->input('iDisplayLength')) $length = $request->input('iDisplayLength');
        else $length = 10;

        $transactions = Transaction::where('agent_id', Auth::user()->id);

        $result = [
            'draw' => 0,
            'recordsTotal' => $transactions->count(),
            'recordsFiltered' => $transactions->count(),
            'data' => []
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
            } else $transactions = $transactions->where('type', $request->input('type_id'));
        }

        $result['recordsFiltered'] = $transactions->count();

        $transactions = $transactions->offset($start)->limit($length);

        $transactions = $transactions->orderBy('created_at', 'DESC');

        $transactions = $transactions->get();

        foreach ($transactions as $transaction) {
            $result['data'][] = [
                $transaction->user->email,
                $transaction->created_at->format('d M Y H:i'),
                $transaction->getDescription(),
                $transaction->getAmount(),
                $transaction->getBonusAmount()
            ];
        }

        return response()->json($result);
    }

    public function feedback(Request $request)
    {

        $validator = Validator::make($request->toArray(), [
            'name' => 'required|string|min:3|max:60',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $errors = [];
        if ($validator->fails()) {
            $validatorErrors = $validator->errors()->toArray();
            array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
                array_push($errors, $item);
            });

            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => $errors,
                    'title' => 'Error',
                    'body' => (string)view('affiliates.parts.body')->with(['data' => $errors])
                ]
            ]);
        }

        //might add try - catch and transaction
        Feedback::create($request->toArray());
        return response()->json([
            'status' => true,
            'message' => [
                'title' => 'Info',
                'body' => (string)view('affiliates.parts.body')->with(['data' => 'We will contact you shortly'])
            ]
        ]);
    }
}
