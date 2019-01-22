<?php

namespace App\Http\Controllers\Auth\Affiliates;

use Hash;
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
        $email = $user->email;
        $confirmEmail = $this->confirmEmail($email);

        if ($confirmEmail['status'] === false) {
            return response()->json($confirmEmail);
        }

        return response()->json([
            'status' => true,
            'message' => [
                'email' => $email,
                'title' => 'Confirm Email',
                'body' => (string)view('affiliates.parts.confirm_email')->with(['email' => $email])
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enter(Request $request)
    {
        $user = Auth::user();
        if (Auth::check()) {
            if ($user->isAgent()) {
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

        $user = User::select(['id', 'email_confirmed'])
            ->where('email', $authData['email'])->first();

        //check user confirm
        if (!is_null($user) and $user->email_confirmed != 1) {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['The email has not confirmed.']
                ]
            ]);
        }

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

    /**
     * @param $userEmail
     * @return array
     */
    protected function confirmEmail($userEmail)
    {
        $user = User::where('email', $userEmail)->first();

        if (is_null($user)) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'User is not found.'
                ]
            ];
        }

        if ($user->email_confirmed == 1) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Something went wrong.'
                ]
            ];
        }

//        $resendMailTime = config('appAdditional.resendMailTime');
//
//        $date = Carbon::now();
//        $date->modify("-$resendMailTime second");
//
//        $activation = UserActivation::where('updated_at', '>=', $date)->where('user_id', $user->id)->first();
//
//        if ($activation) {
//            $resendMailTimeMin = $resendMailTime / 60;
//            return [
//                'status' => false,
//                'message' => [
//                    'errors' => "Mail already sent. You can try in {$resendMailTimeMin} minutes."
//                ]
//            ];
//        }

        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        $link = url('/') . '?confirm=' . $token . '&email=' . $user->email;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if (!$activation) {
            $activation = new UserActivation();
        }

        $activation->user()->associate($user);
        $activation->token = $token;
        $activation->activated = 0;
        $activation->save();

        Mail::queue('emails.partner.confirm', ['link' => $link], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Confirm email');
        });

        return [
            'status' => true,
            'message' => []
        ];
    }

    /**
     * @param $token
     * @param $email
     * @return array
     */
    public function activate($token, $email)
    {
        $linkActiveConfirm = config('appAdditional.linkActiveConfirm');

        $user = User::where('email', $email)->first();
        if (is_null($user)) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'User is not found'
                ]
            ];
        }

        $date = Carbon::now();
        $date->modify("-$linkActiveConfirm day");

        $activation = UserActivation::where([
            ['user_id', '=', $user->id],
            ['token', '=', $token],
            ['updated_at', '>=', $date],
        ])->first();

        if (is_null($activation)) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Invalid token'
                ]
            ];
        }

        if ($user->email_confirmed == 1) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Email already confirmed'
                ]
            ];
        }

        if ($activation) {
            $activation->activated = 1;
            $activation->save();

            $user->email_confirmed = 1;
            $user->save();

            Mail::queue('emails.partner.congratulations', ['email' => $user->email], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Email is now validated');
            });

            return [
                'status' => true,
                'message' => [
                    'messages' => 'Congratulations! E-mail was confirmed!',
                ]
            ];
        } else {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Email wasn\'t confirmed. Invalid link.'
                ]
            ];
        }
    }
}
