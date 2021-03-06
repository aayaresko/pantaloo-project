<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\ModernExtraUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
        //preparation params
        $errors = [];
        $mode = $request->wantsJson() ? 1 : 0;
        $data = $request->all();

        try {
            //set locale
            $lang = $request->cookie('langs');
            $languages = \Helpers\GeneralHelper::getListLanguage();
            if (in_array($lang, $languages)) {
                app()->setLocale($lang);
            }
            //set locale

            //custom validation
            $validator = Validator::make($data, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                $validatorErrors = $validator->errors()->toArray();
                array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
                    array_push($errors, $item);
                });
                throw new \Exception('validation');
            }
            //custom validation

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                $sendLockoutResponse = $this->sendLockoutResponse($request);
                $errors = $sendLockoutResponse;
                throw new \Exception('lockoutResponse');
            }

            if (!$this->attemptLogin($request)) {
                $this->incrementLoginAttempts($request);

                $sendFailedLoginResponse = $this->sendFailedLoginResponse($request);
                $errors = $sendFailedLoginResponse;
                throw new \Exception('failedLoginResponse');
            }

            $user = Auth::user();
            $roleUser = (int)Auth::user()->role;

            if (array_search($roleUser, [1, 3]) !== false) {
                Auth::logout();
                $errors = ['This type of user is not allowed to login'];
                throw new \Exception('userType');
            }

            //$extraUser = ExtraUser::where('user_id', $user->id)->first();

            $blockUser = ModernExtraUsers::where('user_id', $user->id)
                ->where('code', 'block')->first();

            if (!is_null($blockUser)) {
                if ((int)$blockUser->value === 1) {
                    //delete global session TO DO
                    Auth::logout();
                    $errors = ['The user is blocked'];
                    throw new \Exception('userBlock');
                }
            }

            $messages = $this->sendLoginResponse($request);
        } catch (\Exception $ex) {
            if (empty($errors)) {
                $errors = ['Some is wrong'];
            }

            if ($mode == 1) {
                return response()->json([
                    'status' => false,
                    'message' => [
                        'errors' => $errors
                    ]
                ]);
            }

            return redirect()->back()->withErrors($errors);
        }

        if ($mode == 1) {
            return response()->json([
                'status' => true,
                'message' => $messages
            ]);
        }

        return redirect($this->redirectPath());

    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return [Lang::get('auth.throttle', ['seconds' => $seconds])];
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return ['Done'];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return [trans('auth.failed')];
    }

//    public function login(Request $request)
//    {
//        $this->validateLogin($request);
//
//        // If the class is using the ThrottlesLogins trait, we can automatically throttle
//        // the login attempts for this application. We'll key this by the username and
//        // the IP address of the client making these requests into this application.
//        if ($this->hasTooManyLoginAttempts($request)) {
//            $this->fireLockoutEvent($request);
//
//            return $this->sendLockoutResponse($request);
//        }
//
//        if ($this->attemptLogin($request)) {
//            $user = Auth::user();
//            $roleUser = (int) Auth::user()->role;
//
//            if (array_search($roleUser, [1, 3]) !== false) {
//                Auth::logout();
//
//                return back()->withErrors('This type of user is not allowed to login');
//            }
//
//            //$extraUser = ExtraUser::where('user_id', $user->id)->first();
//
//            $blockUser = ModernExtraUsers::where('user_id', $user->id)
//                ->where('code', 'block')->first();
//
//            if (! is_null($blockUser)) {
//                if ((int) $blockUser->value === 1) {
//                    //delete global session TO DO
//                    Auth::logout();
//
//                    return back()->withErrors('The user is blocked');
//                }
//            }
//
//            return $this->sendLoginResponse($request);
//        }
//        // If the login attempt was unsuccessful we will increment the number of attempts
//        // to login and redirect the user back to the login form. Of course, when this
//        // user surpasses their maximum number of attempts they will get locked out.
//        $this->incrementLoginAttempts($request);
//
//        return $this->sendFailedLoginResponse($request);
//    }
}
