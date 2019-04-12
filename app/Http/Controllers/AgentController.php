<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Tracker;
use Illuminate\Http\Request;
use DB;
use App\ExtraUser;
use App\Http\Requests;
use App\Transaction;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Bitcoin\Service;


class AgentController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            if (Auth::user()->isAgent()) return redirect()->route('agent.dashboard');
            else return redirect('/');
        }

        return view('agent.login');
    }

    public function enter(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->isAgent()) return redirect()->route('agent.dashboard');
            else return redirect()->url('/');
        }

        if ($request->input('remember_me') == 'on') $remember = true;
        else $remember = false;

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'role' => 1], $remember)) {
            return redirect()->route('agent.dashboard');
        } else return redirect()->route('agent.login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('agent.login');
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
            'campaignlink' => 'required',
//            'ref' => 'required|max:50|alpha_num'
        ]);

        //get config
        $hashLength = 12;
        $ref = Str::random($hashLength);

//        $includeName = $request->input('include_name');
//        if (!is_null($includeName) and $includeName === 'on') {
//            $ref = $ref . '&CampaignName=' . $request->name;
//        }

        if (Tracker::where('ref', $ref)->count() != 0) return redirect()->back()->withErrors(['Ref already exists']);

        $tracker = new Tracker();
//        $tracker->ref = $request->input('ref');
        $tracker->ref = $ref;
        $tracker->name = $request->input('name');
        $tracker->campaignlink = $request->input('campaignlink');
        $tracker->user()->associate(Auth::user());
        $tracker->save();

        return redirect()->back()->with('msg', 'Tracker was created');
    }

    public function updateTracker(Tracker $tracker, Request $request)
    {
        if ($tracker->user_id != Auth::user()->id) return redirect()->back();

        $this->validate($request, [
            'name' => 'required|max:50',
            'campaignlink' => 'required'
        ]);

        $tracker->name = $request->input('name');
        $tracker->campaignlink = $request->input('campaignlink');
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
//        $this->validate($request, [
//            'address' => 'required'
//        ]);
//
//        $sum = Auth::user()->getAgentAvailable();
//
//        if ($sum < 1) return redirect()->back()->withErrors(['Minimum sum is 1 mBtc']);
//
//        $service = new Service();
//        if (!$service->isValidAddress($request->input('address'))) return redirect()->back()->withErrors(['Invalid bitcoin address']);
//
//        $payment = new Payment();
//        $payment->sum = $sum;
//        $payment->user()->associate(Auth::user());
//        $payment->address = $request->input('address');
//        $payment->status = 0;
//        $payment->save();
//
//        return redirect()->back()->with('msg', 'Withdraw request was created');

        $this->validate($request, [
            'address' => 'required'
        ]);

        $user = Auth::user();

        $sum = $user->getAgentAvailable();

        if (Auth::user()->email_confirmed == 0) {
            return redirect()->back()->withErrors(['E-mail confirmation required']);
        }

        if ($sum < 1) {
            return redirect()->back()->withErrors(['Minimum sum is 1 mBtc']);
        }

        $service = new Service();
        if (!$service->isValidAddress($request->input('address'))) {
            return redirect()->back()->withErrors(['Invalid bitcoin address']);
        }

        //create payment
        $payment = new Payment();
        $payment->sum = $sum;
        $payment->user()->associate(Auth::user());
        $payment->address = $request->input('address');
        $payment->status = 0;
        $payment->save();
        //end create payment

        $sum = -1 * $sum;
        $transaction = new Transaction();
        $transaction->sum = $sum;
        $transaction->bonus_sum = 0;
        $transaction->user()->associate(Auth::user());
        $transaction->type = 4;
        $transaction->withdraw_status = 0;
        $transaction->address = $request->input('address');
        $transaction->save();
        //edit access

        return redirect()->back()->with('msg', 'Withdraw request was created');
    }

    public function all()
    {
        $baseLineCpaDefault = config('appAdditional.defaultmBtcCpu');

        $agents = User::leftJoin('extra_users as extra', 'users.id', '=', 'extra.user_id')
            ->where('role', 1)
            ->select(
                [
                    '*', 'users.id as id',
                    DB::raw("IF(extra.base_line_cpa is null, $baseLineCpaDefault, extra.base_line_cpa) as base_line_cpa"),
                    'extra.block'
                ])
            ->get();

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
            'commission' => 'required|numeric|min:0|max:100',
            'base_line_cpa' => 'required|numeric',
            'block' => 'integer',
        ]);

        if ($request->has('base_line_cpa') or $request->has('block')) {

            $block = ($request->has('block')) ? 1 : 0;
            $extraUser = ExtraUser::where('user_id', $user->id)->first();
            if (is_null($extraUser)) {
                //add
                ExtraUser::create([
                    'user_id' => $user->id,
                    'block' => $block,
                    'base_line_cpa' => $request->base_line_cpa
                ]);
            } else {
                //update value
                ExtraUser::where('user_id', $user->id)->update([
                    'block' => $block,
                    'base_line_cpa' => $request->base_line_cpa
                ]);
            }
        }

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
