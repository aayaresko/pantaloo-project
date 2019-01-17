<?php

namespace App\Http\Controllers\Auth\Affiliates;

use App\User;
use Validator;
use App\Tracker;
use App\Currency;
use Carbon\Carbon;
use App\UserActivation;
use App\Bitcoin\Service;
use Illuminate\Http\Request;
use App\Jobs\SetUserCountry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth\Affiliates
 */
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins, DispatchesJobs;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $loginPath = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        //send email confirmation
        $this->confirmEmail($user);

        if (Auth::attempt($authData, false)) {
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
                    'errors' => ['Something went wrong'],
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('affiliates.index');
    }

    public function confirmEmail($user)
    {
        if ($user->email_confirmed == 1) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Something went wrong.'
                ]
            ];
        }

        $resendMailTime = config('appAdditional.resendMailTime');

        $date = Carbon::now();
        $date->modify("-$resendMailTime second");

        $activation = UserActivation::where('updated_at', '>=', $date)->where('user_id', $user->id)->first();

        if ($activation) {
            $resendMailTimeMin = $resendMailTime / 60;
            return [
                'status' => false,
                'message' => [
                    'errors' => "Mail already sent. You can try in {$resendMailTimeMin} minutes."
                ]
            ];
        }

        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        $link = url('/') . '/affiliates/activate/' . $token;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if (!$activation) {
            $activation = new UserActivation();
        }

        $activation->user()->associate($user);
        $activation->token = $token;
        $activation->activated = 0;
        $activation->save();

        Mail::queue('email.confirm', ['link' => $link], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Confirm email');
        });

        return [
            'status' => true,
            'message' => [
                'E-mail confirmation',
                'Success',
                'We sent you confirmation link. Check your mail please.',
            ]
        ];

    }

    public function activate($token)
    {
        $date = Carbon::now();
        $date->modify('-1 day');

        $user = Auth::user();
        $linkActiveConfirm = config('appAdditional.linkActiveConfirm');
        if ($user->isConfirmed()) return redirect('/')->withErrors(['Email already confirmed']);

        $activation = UserActivation::where('user_id', $user->id)->where('token', $token)->where('updated_at', '>=', $date)->first();

        if ($activation) {
            $activation->activated = 1;
            $activation->save();

            $user->email_confirmed = 1;
            $user->save();

            return redirect('/')->with('popup', ['E-mail confirmation', 'Success', 'Congratulations! E-mail was confirmed!']);
        } else {
            return redirect('/')->withErrors(['Email wasn\'t confirmed. Invalid link.']);
        }
    }
}
