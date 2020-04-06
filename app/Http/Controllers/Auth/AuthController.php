<?php

namespace App\Http\Controllers\Auth;

use App\Country;
use DB;
use App\User;
use Validator;
use App\Tracker;
use App\ExtraUser;
use App\Currency;
use App\UserActivation;
use App\Bitcoin\Service;
use App\ModernExtraUsers;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Jobs\SetUserCountry;
use App\Models\StatisticalData;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Validators\TemporaryMailCheck;
use App\Providers\EmailChecker\EmailChecker;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


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
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'agree' => 'required',
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'agree' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }


//        $betatest = Cookie::get('betatest');
//
//        if ((int)$betatest !== 1) {
//            return redirect()->back()->withErrors(['Due to high demand we are experiencing technical difficulties.
//             Registration are temporary disabled. Sorry for the inconvenience.']);
//        }

        //to do create normal controller for auth
        $appAdditional = config('appAdditional');
        $codeCountryCurrent = GeneralHelper::visitorCountryCloudFlare();
        $disableRegistrationCountry = config('appAdditional.disableRegistration');

        if (!GeneralHelper::isTestMode() and in_array($codeCountryCurrent, $disableRegistrationCountry)) {
            return redirect()->back()->withErrors(['REGISTRATIONS ARE NOT AVAILABLE IN YOUR REGION.']);
        }

        $emailChecker = new EmailChecker();

        if (!GeneralHelper::isTestMode() and $emailChecker->isInvalidEmail($request->email)) {
            return redirect()->back()->withErrors(['Please try another email service!']);
        }
        //end validation

        //act
        try {
            /*if (GeneralHelper::isTestMode()) {
                $address = 'bitcoinTestAddress';
            } else {
                $service = new Service();
                $address = $service->getNewAddress('common');
            }*/

            $service = new Service();
            $address = $service->getNewAddress();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);

            $user->bitcoin_address = $address;
            $user->balance = 0;

            $ip = GeneralHelper::visitorIpCloudFlare();
            $user->ip = $ip;

            $tracker_id = Cookie::get('tracker_id');
            $tracker = false;
            if ($tracker_id) {
                $tracker = Tracker::find($tracker_id);
            } elseif (isset($data['ref']) and $data['ref']) {
                $tracker = Tracker::where('ref', $data['ref'])->first();
            }

            if ($tracker) {
                $user->tracker()->associate($tracker);
                $user->agent_id = $tracker->user_id;

                //set count for this registration
                $eventStatistic = $appAdditional['eventStatistic'];
                StatisticalData::create([
                    'event_id' => $eventStatistic['register'],
                    'value' => 'register',
                    'tracker_id' => $tracker->id
                ]);
                //set count for this registration
            }

            //this temporary decision
            $data['currency'] = 1;
            $currency = Currency::find($data['currency']);

            if ($currency) {
                $user->currency_id = $currency->id;
            }

            $user->save();

            //welcome bonus set param for active
            $configBonus = config('bonus');
            $configSetWelcome = $configBonus['setWelcomeBonus'];
            if (Cookie::get($configSetWelcome['name']) === $configSetWelcome['value']) {
                ModernExtraUsers::create([
                    'user_id' => $user->id,
                    'code' => 'freeEnabled',
                    'value' => $configSetWelcome['value']
                ]);
            }
            //welcome bonus set param for active

            //send email
            //to do check this
            $token = hash_hmac('sha256', str_random(40), config('app.key'));
            $link = url('/') . '/activate/' . $token . '/email/' . $user->email;

            $activation = UserActivation::where('user_id', $user->id)->first();

            if (!$activation) $activation = new UserActivation();

            $activation->user()->associate($user);
            $activation->token = $token;
            $activation->activated = 0;

            $activation->save();

            Mail::queue('emails.confirm', ['link' => $link], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Confirm email');
            });

            $this->dispatch(new SetUserCountry($user));
            if (!$user->agent_id) {
                $country_code = GeneralHelper::visitorCountryCloudFlare();
                $country = Country::where('code', $country_code)->first();
                $agent = $country ? $country->user->first() : false;
                if ($agent) {
                    $user->agent_id = $agent->id;
                    $user->save();
                }
            }

            Auth::guard($this->getGuard())->login($user);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['Something went wrong']);
        }

        return redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */

    protected function create(array $data)
    {
//        //temporary
//        $data = $request->toArray();

//        $errors = [];
//        $validator = Validator::make($data, [
//            'email' => 'required|email|max:255|unique:users|unique:new_affiliates',
//            'agree' => 'accepted'
//        ]);
//
//        // Check if mail provider is not temporary mail services
//        $validator->after(function ($validator) use ($data) {
//            if (TemporaryMailCheck::isTemporaryMailService($data['email'])) {
//                $validator->errors()->add('email', 'Try use other mail service!');
//            }
//        });
//
//        if ($validator->fails()) {
//            $validatorErrors = $validator->errors()->toArray();
//            array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
//                array_push($errors, $item);
//            });
//
//            return redirect()->back()->withErrors($errors);
//        }
//
//        $email = $data['email'];
//        $currentDate = new \DateTime();
//
//        DB::table('new_affiliates')->insert([
//            [
//                'email' => $email,
//                'type_id' => 1,
//                'created_at' => $currentDate,
//                'updated_at' => $currentDate
//            ],
//        ]);
//
//        return redirect()->back()->with('popup',
//            ['Success', 'Register a CasinoBit', 'Thank you for understanding! We will contact you!']);

        //temporary

//        $betatest = Cookie::get('betatest');
//
//        if ((int)$betatest !== 1) {
//            return redirect()->back()->withErrors(['Registration is closed']);
//        }
//
//        $validator =  Validator::make($data, [
//            'name' => 'required|max:255',
//            'email' => 'required|email|max:255|unique:users',
//            'password' => 'required|min:6|confirmed',
//            'agree' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            $errors = $validator->errors();
//            return redirect()->back()->withErrors($errors);
//        }
        //start
        $service = new Service();
        $address = $service->getNewAddress();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
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
        } elseif (isset($data['ref']) and $data['ref']) {
            $tracker = Tracker::where('ref', $data['ref'])->first();
        }

        if ($tracker) {
            $user->tracker()->associate($tracker);
            $user->agent_id = $tracker->user_id;

            //set count for this registration
            $appAdditional = config('appAdditional');
            $eventStatistic = $appAdditional['eventStatistic'];
            StatisticalData::create([
                'event_id' => $eventStatistic['register'],
                'value' => 'register',
                'tracker_id' => $tracker->id
            ]);
            //set count for this registration
        }

        //this temporary decision
        $data['currency'] = 1;
        $currency = Currency::find($data['currency']);

        if ($currency) {
            $user->currency_id = $currency->id;
        }

        $user->save();

        //send email
        //to do check this
        $token = hash_hmac('sha256', str_random(40), config('app.key'));
        $link = url('/') . '/activate/' . $token . '/email/' . $user->email;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if (!$activation) $activation = new UserActivation();

        $activation->user()->associate($user);
        $activation->token = $token;
        $activation->activated = 0;

        $activation->save();

        Mail::queue('emails.confirm', ['link' => $link], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Confirm email');
        });

        //'Email is now validated'
//        Mail::queue('emails.congratulations', ['email' => $user->email], function ($m) use ($user) {
//            $m->to($user->email, $user->name)->subject('Welcome to CasinoBit.io!');
//        });
        //to do check this

        $this->dispatch(new SetUserCountry($user));

        return $user;
    }

    public function share()
    {

    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            $roleUser = (int)Auth::user()->role;

            if (array_search($roleUser, [1, 3, 4]) !== false) {
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
            return $this->handleUserWasAuthenticated($request, $throttles);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function registerModern(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'agree' => 'required',
        ]);

        if ($validator->fails()) {
            $validatorErrors = $validator->errors()->toArray();

            // array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
            //     array_push($errors, $item);
            // });
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['jhgjhgjh']
                ]
            ]);
        }

        //to do create normal controller for auth
        $codeCountryCurrent = GeneralHelper::visitorCountryCloudFlare();
        $disableRegistrationCountry = config('appAdditional.disableRegistration');

        if (in_array($codeCountryCurrent, $disableRegistrationCountry)) {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['REGISTRATIONS ARE NOT AVAILABLE IN YOUR REGION.']
                ]
            ]);
        }

        $emailChecker = new EmailChecker();

        if ($emailChecker->isInvalidEmail($request->email)) {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['Please try another email service!']
                ]
            ]);
        }
        //end validation

        //act
        try {
            /*if (GeneralHelper::isTestMode()) {
                $address = 'bitcoinTestAddress';
            } else {
                $service = new Service();
                $address = $service->getNewAddress('common');
            }*/

            $service = new Service();
            $address = $service->getNewAddress();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);

            $user->bitcoin_address = $address;
            $user->balance = 0;

            $ip = GeneralHelper::visitorIpCloudFlare();
            $user->ip = $ip;

            $tracker_id = Cookie::get('tracker_id');

            if ($tracker_id) {
                $tracker = Tracker::find($tracker_id);

                if ($tracker) {
                    $user->tracker()->associate($tracker);
                    $user->agent_id = $tracker->user_id;

                    //set count for this registration
                    $appAdditional = config('appAdditional');
                    $eventStatistic = $appAdditional['eventStatistic'];
                    StatisticalData::create([
                        'event_id' => $eventStatistic['register'],
                        'value' => 'register',
                        'tracker_id' => $tracker->id
                    ]);
                    //set count for this registration
                }
            }

            //this temporary decision
            $data['currency'] = 1;
            $currency = Currency::find($data['currency']);

            if ($currency) {
                $user->currency_id = $currency->id;
            }

            $user->save();

            //send email
            //to do check this
            $token = hash_hmac('sha256', str_random(40), config('app.key'));
            $link = url('/') . '/activate/' . $token . '/email/' . $user->email;

            $activation = UserActivation::where('user_id', $user->id)->first();

            if (!$activation) $activation = new UserActivation();

            $activation->user()->associate($user);
            $activation->token = $token;
            $activation->activated = 0;

            $activation->save();

            Mail::queue('emails.confirm', ['link' => $link], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Confirm email');
            });

            $this->dispatch(new SetUserCountry($user));

            Auth::guard($this->getGuard())->login($user);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['Something went wrong']
                ]
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function loginModern(Request $request)
    {
        if (1) {
            return response()->json([
                'status' => true,
                'message' => ['ERROR']
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }
}
