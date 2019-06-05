<?php

namespace App\Http\Controllers\Auth;

use App\Mail\BaseMailable;
use App\Mail\EmailConfirm;
use App\User;
use Illuminate\Mail\Mailable;
use Validator;
use App\Tracker;
use App\Currency;
use App\UserActivation;
use App\Bitcoin\Service;
use Helpers\GeneralHelper;
use App\Jobs\SetUserCountry;
use Illuminate\Http\Request;
use App\Models\StatisticalData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use App\Providers\EmailChecker\EmailChecker;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
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
        $this->middleware('guest');
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

        $validator = $this->validator($data);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        //to do create normal controller for auth
        $codeCountryCurrent = GeneralHelper::visitorCountryCloudFlare();
        $disableRegistrationCountry = config('appAdditional.disableRegistration');

        if (!GeneralHelper::isTestMode() && in_array($codeCountryCurrent, $disableRegistrationCountry)) {
            return redirect()->back()->withErrors(['REGISTRATIONS ARE NOT AVAILABLE IN YOUR REGION.']);
        }

        $emailChecker = new EmailChecker();

        if ($emailChecker->isInvalidEmail($request->email)) {
            return redirect()->back()->withErrors(['Please try another email service!']);
        }
        //end validation

        //act
        try {
            if (GeneralHelper::isTestMode()) {
                $address = 'bitcoinTestAddress';
            } else {
                $service = new Service();
                $address = $service->getNewAddress('common');
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
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
                        'tracker_id' => $tracker->id,
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

            if (!$activation) {
                $activation = new UserActivation();
            }

            $activation->user()->associate($user);
            $activation->token = $token;
            $activation->activated = 0;

            $activation->save();

            $mail = new BaseMailable('emails.confirm', ['link' => $link]);
            $mail->subject('Confirm email');
            Mail::to($user)->send($mail);

            $this->dispatch(new SetUserCountry($user));

            $this->guard()->login($user);
        } catch (\Exception $ex) {
            dd($ex);
            return redirect()->back()->withErrors([$ex->getMessage()]);
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
