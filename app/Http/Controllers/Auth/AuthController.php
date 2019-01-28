<?php

namespace App\Http\Controllers\Auth;

use App\Bitcoin\Service;
use App\Currency;
use App\Jobs\SetUserCountry;
use App\Tracker;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Validator;
use App\Models\StatisticalData;
use Illuminate\Support\Facades\Mail;
use Helpers\GeneralHelper;
use App\UserActivation;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Cookie;

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
     * @param  array $data
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
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $service = new Service();
        $address = $service->getNewAddress('common');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //set count for this registration
        $appAdditional = config('appAdditional');
        $eventStatistic = $appAdditional['eventStatistic'];
        StatisticalData::create([
            'event_id' => $eventStatistic['register'],
            'value' => 'register'
        ]);
        //set count for this registration

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
        $link = url('/') . '/activate/' . $token;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if(!$activation) $activation = new UserActivation();

        $activation->user()->associate($user);
        $activation->token = $token;
        $activation->activated = 0;

        $activation->save();

        Mail::queue('emails.confirm', ['link' => $link], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Confirm email');
        });
        //to do check this

        $this->dispatch(new SetUserCountry($user));

        return $user;
    }

    public function share()
    {

    }
}
