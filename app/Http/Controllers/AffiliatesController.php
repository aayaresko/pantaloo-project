<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Payment;
use App\Tracker;
use App\Currency;
use Carbon\Carbon;
use App\Bitcoin\Service;
use App\Jobs\SetUserCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\ResetsPasswords;

class AffiliatesController extends Controller
{
    use ResetsPasswords;

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

    public function enter(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->isAgent()) {
                return response()->json([
                    'status' => true,
                    'message' => [
                        'redirect' => '/affiliates/dashboard'
                    ]
                ]);
            }
        }

        if ($request->input('remember_me') == 'on') {
            $remember = true;
        } else {
            $remember = false;
        }

        $authData = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role' => 1
        ];

        if (Auth::attempt($authData, $remember)) {
            return response()->json([
                'status' => true,
                'message' => [
                    'redirect' => '/affiliates',
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['These credentials do not match our records.']
                ]
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('affiliates.index');
    }

    public function register(Request $request)
    {
        $data = $request->toArray();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
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
                    'errors' => $errors
                ]
            ]);
        }

        $service = new Service();
        $address = $service->getNewAddress('common');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->bitcoin_address = $address;
        $user->balance = 0;

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $user->ip = $ip;
        }

        $tracker_id = Cookie::get('tracker_id');

        if ($tracker_id) {
            $tracker = Tracker::find($tracker_id);

            if ($tracker) {
                $user->tracker()->associate($tracker);
                $user->agent_id = $tracker->user_id;
            }
        }

        $currency = Currency::find(1);

        if ($currency) {
            $user->currency_id = $currency->id;
        }

        $user->role = 1;

        $user->save();

        $this->dispatch(new SetUserCountry($user));

        $authData = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role' => 1
        ];

        if (Auth::attempt($authData, false)) {
            return response()->json([
                'status' => true,
                'message' => [
                    'redirect' => '/affiliates',
                ]
            ]);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'email' => 'required|email'
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
                    'errors' => $errors
                ]
            ]);
        }

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'status' => true,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
            case Password::INVALID_USER:
            default:
            return response()->json([
                'status' => false,
                'message' => [
                    'response' => $response,
                ]
            ]);
        }
    }


    public function reset(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $this->getResetCredentials($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->json([
                    'status' => true,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
            default:
                return response()->json([
                    'status' => false,
                    'message' => [
                        'response' => $response,
                    ]
                ]);
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return redirect()->route('affiliates.index');
        }

        $email = $request->input('email');
        return view('affiliates.reset_password')->with(compact('token', 'email'));
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
