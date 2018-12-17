<?php

namespace App\Http\Controllers\Auth\Affiliates;

use App\User;
use Validator;
use App\Tracker;
use App\Currency;
use App\Bitcoin\Service;
use Illuminate\Http\Request;
use App\Jobs\SetUserCountry;
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
}