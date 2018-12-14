<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Payment;
use App\Tracker;
use Carbon\Carbon;
use App\Bitcoin\Service;
use Illuminate\Http\Request;
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


    public function dashboard(Request $request)
    {
        try {
            $from = Carbon::createFromFormat("Y-m-d", $request->input('start'));
        } catch (\Exception $e) {
            $from = Carbon::now();
        }

        try {
            $to = Carbon::createFromFormat("Y-m-d", $request->input('end'));
        } catch (\Exception $e) {
            $to = Carbon::now();
        }

        $to->setTime(23, 59, 59);
        $from->setTime(0, 0, 0);

        $transactions = collect();

        $users = User::where('agent_id', Auth::user()->id)->get();

        $result = collect();

        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            //$stat['profit'] = $stat['revenue'] * Auth::user()->commission / 100;

            foreach ($stat as $key => $value)
                $stat[$key] = round($value, 2);

            $stat['user'] = $user;

            $result->push($stat);
        }

        $trackers = collect();

        foreach (Auth::user()->trackers as $tracker) {
            $stat = $tracker->stat($from, $to);

            $stat['tracker'] = $tracker->name;

            $trackers->push($stat);
        }

        $data = [
            'users' => $result,
            'trackers' => $trackers,
            'deposit_total' => $result->sum('deposits'),
            'bonus_total' => $result->sum('bonus'),
            'revenue_total' => $result->sum('revenue'),
            'profit_total' => $result->sum('profit')
        ];

        return view('agent.dashboard', $data);
    }

    public function trackers()
    {
        $trackers = Auth::user()->trackers;

        return view('agent.trackers', ['trackers' => $trackers]);
    }

    public function storeTracker(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'ref' => 'required|max:50|alpha_num'
        ]);

        if (Tracker::where('ref', $request->input('ref'))->count() != 0) return redirect()->back()->withErrors(['Ref already exists']);

        $tracker = new Tracker();
        $tracker->ref = $request->input('ref');
        $tracker->name = $request->input('name');
        $tracker->user()->associate(Auth::user());
        $tracker->save();

        return redirect()->back()->with('msg', 'Tracker was created');
    }

    public function updateTracker(Tracker $tracker, Request $request)
    {
        if ($tracker->user_id != Auth::user()->id) return redirect()->back();

        $this->validate($request, [
            'name' => 'required|max:50'
        ]);

        $tracker->name = $request->input('name');
        $tracker->save();

        return redirect()->back()->with('msg', 'Tracker was updated');
    }

    public function withdraw()
    {
        $available = Auth::user()->getAgentAvailable();

        return view('agent.withdraw', ['available' => $available, 'payments' => Auth::user()->payments]);
    }

    public function withdrawDo(Request $request)
    {
        $this->validate($request, [
            'address' => 'required'
        ]);

        $sum = Auth::user()->getAgentAvailable();

        if ($sum < 1) return redirect()->back()->withErrors(['Minimum sum is 1 mBtc']);

        $service = new Service();
        if (!$service->isValidAddress($request->input('address'))) return redirect()->back()->withErrors(['Invalid bitcoin address']);

        $payment = new Payment();
        $payment->sum = $sum;
        $payment->user()->associate(Auth::user());
        $payment->address = $request->input('address');
        $payment->status = 0;
        $payment->save();

        return redirect()->back()->with('msg', 'Withdraw request was created');
    }

    public function all()
    {
        $agents = User::where('role', 1)->get();

        $result = [];

        foreach ($agents as $agent) {
            $item = [
                'agent' => $agent,
                'available' => $agent->getAgentAvailable(),
                'users' => User::where('agent_id', $agent->id)->count(),
                'procent' => $agent->commission,
                'total' => $agent->getAgentTotal()
            ];

            $result[] = $item;
        }

        return view('admin.agents', ['agents' => $result]);
    }

    public function commission(User $user, Request $request)
    {
        if ($user->role != 1) return redirect()->back()->withErrors(['User not agent']);

        $this->validate($request, [
            'commission' => 'required|numeric|min:0|max:100'
        ]);

        $user->commission = $request->input('commission');
        $user->save();

        return redirect()->back()->with('msg', 'Agent was updated');
    }

    public function payments()
    {
        $payments = Payment::all();

        return view('admin.agentPayments', [
            'payments' => $payments
        ]);
    }
}
