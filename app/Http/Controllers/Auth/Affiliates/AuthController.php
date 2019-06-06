<?php

namespace App\Http\Controllers\Auth\Affiliates;

use DB;
use App\User;
use Validator;
use App\Tracker;
use App\Currency;
use App\ExtraUser;
use Carbon\Carbon;
use App\UserActivation;
use App\Bitcoin\Service;
use App\Mail\BaseMailable;
use App\Mail\EmailConfirm;
use Helpers\GeneralHelper;
use App\Jobs\SetUserCountry;
use Illuminate\Http\Request;
use App\Mail\EmailPartnerConfirm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Validators\TemporaryMailCheck;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\ThrottlesLogins;

/**
 * Class AuthController.
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

    use ThrottlesLogins, DispatchesJobs;

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
        //temporary
//        $errors = [];
//        $data = $request->toArray();
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
//            return response()->json([
//                'status' => false,
//                'message' => [
//                    'errors' => $errors
//                ]
//            ]);
//        }
//
//        $email = $request->email;
//        $currentDate = new \DateTime();
//
//        DB::table('new_affiliates')->insert([
//            [
//                'email' => $email,
//                'type_id' => 2,
//                'created_at' => $currentDate,
//                'updated_at' => $currentDate
//            ],
//        ]);
//
//        return response()->json([
//            'status' => true,
//            'message' => [
//                'email' => $email,
//                'title' => 'Register a CasinoBit Affiliate Account',
//                'body' => '<h4>Thank you for understanding! We will contact you!</h4>'
//            ]
//        ]);

        //normal code
        $data = $request->toArray();
        $validator = Validator::make($data, [
            'name' => 'string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $errors = [];
        $partnerCommission = config('appAdditional.partnerCommission');

        if ($validator->fails()) {
            $validatorErrors = $validator->errors()->toArray();
            array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
                array_push($errors, $item);
            });

            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => $errors,
                ],
            ]);
        }

        $service = new Service();
        $address = $service->getNewAddress('common');

        if (isset($data['name'])) {
            $name = $data['name'];
        } else {
            $name = 'no_name';
        }

        $user = User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'commission' => $partnerCommission,
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
                'body' => (string) view('affiliates.parts.confirm_email')->with(['email' => $email]),
            ],
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
                        'redirect' => '/affiliates/dashboard',
                    ],
                ]);
            }
        }

        if ($request->input('remember_me') == 'on') {
            $remember = true;
        } else {
            $remember = false;
        }

        //to do config
        $allowRoles = [1, 3];
        $email = $request->input('email');

        $user = User::select(['id', 'email_confirmed', 'role'])
            ->where('email', $email)->first();

        //check user confirm
        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['These credentials do not match our records.'],
                ],
            ]);
        }

        if ($user->email_confirmed != 1) {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['The email has not confirmed.'],
                ],
            ]);
        }

        $roleUser = (int) $user->role;
        $allowRoleKey = array_search($roleUser, $allowRoles);
        if ($allowRoleKey === false) {
            $allowRole = $allowRoles[0];
        } else {
            $allowRole = $allowRoles[$allowRoleKey];
        }

        $authData = [
            'email' => $email,
            'password' => $request->input('password'),
            'role' => $allowRole,
        ];

        if (Auth::attempt($authData, $remember)) {
            $user = Auth::user();
            $extraUser = ExtraUser::where('user_id', $user->id)->first();
            if (! is_null($extraUser)) {
                if ((int) $extraUser->block > 0) {
                    Auth::logout();

                    return response()->json([
                        'status' => false,
                        'message' => [
                            'errors' => ['The user is blocked'],
                        ],
                    ]);
                }
            }
            //to do super affiliates
            switch ($roleUser) {
                case 1:
                    return response()->json([
                        'status' => true,
                        'message' => [
                            'redirect' => '/affiliates',
                        ],
                    ]);
                case 3:
                    return response()->json([
                        'status' => true,
                        'message' => [
                            'redirect' => '/admin',
                        ],
                    ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => ['These credentials do not match our records.'],
                ],
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
                    'errors' => 'User is not found.',
                ],
            ];
        }

        if ($user->email_confirmed == 1) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Something went wrong.',
                ],
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

        $token = GeneralHelper::generateToken();

        $link = url('/').'?confirm='.$token.'&email='.$user->email;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if (! $activation) {
            $activation = new UserActivation();
        }

        $activation->user()->associate($user);
        $activation->token = $token;
        $activation->activated = 0;
        $activation->save();

        $mail = new BaseMailable('emails.partner.confirm', ['link' => $link]);
        $mail->subject('Confirm email');
        Mail::to($user)->send($mail);

        return [
            'status' => true,
            'message' => [],
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
                    'errors' => 'User is not found',
                ],
            ];
        }

        $date = Carbon::now();
        $date->modify("-$linkActiveConfirm day");

        $activation = UserActivation::where([
            ['user_id', '=', $user->id],
            ['token', '=', $token],
            ['updated_at', '>=', $date],
        ])->first();

//        if (is_null($activation)) {
//            return [
//                'status' => false,
//                'message' => [
//                    'errors' => 'Invalid token'
//                ]
//            ];
//        }

        if ($user->email_confirmed == 1) {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Email already confirmed',
                ],
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
                ],
            ];
        } else {
            return [
                'status' => false,
                'message' => [
                    'errors' => 'Email wasn\'t confirmed. Invalid link.',
                ],
            ];
        }
    }
}
