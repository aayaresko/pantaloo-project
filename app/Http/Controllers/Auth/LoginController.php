<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\ModernExtraUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            $roleUser = (int)Auth::user()->role;

            if (array_search($roleUser, [1, 3]) !== false) {
                Auth::logout();

                return back()->withErrors('This type of user is not allowed to login');
            }

            //$extraUser = ExtraUser::where('user_id', $user->id)->first();

            $blockUser = ModernExtraUsers::where('user_id', $user->id)
                ->where('code', 'block')->first();

            if (!is_null($blockUser)) {
                if ((int)$blockUser->value === 1) {
                    //delete global session TO DO
                    Auth::logout();

                    return back()->withErrors('The user is blocked');
                }
            }

            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
