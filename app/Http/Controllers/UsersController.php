<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Domain;
use Carbon\Carbon;
use App\Http\Requests;
use Mockery\Exception;
use App\UserActivation;
use App\ModernExtraUsers;
use App\Mail\BaseMailable;
use App\Mail\EmailConfirm;
use App\Models\AgentsKoef;
use Illuminate\Support\Str;
use App\Jobs\SetUserCountry;
use Illuminate\Http\Request;
use App\Events\AccountStatusEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        switch ($user->role) {
            case 2:
                $filterData = [];
                $configUser = config('appAdditional.users');
                $userTypes = $configUser['roles'];

                //to do block to config value
                $users = User::select(['users.*', DB::raw('IFNULL(block.value, 0) as block')])
                    ->leftJoin('modern_extra_users as block', function ($join) {
                        $join->on('users.id', '=', 'block.user_id')
                            ->where('block.code', '=', 'block');
                    });
                if ($request->email) {
                    $filterData['email'] = $request->email;
                    $users->where('users.email', 'like', '%' . $request->email . '%');
                }

                //to do fix this temporary
                if ($request->filled('role')) {
                    $role = $request->role;
                    $filterData['role'] = is_numeric($role) ? (int)$role : $role;

                    switch ($request->role) {
                        case 'all':
                            break;
                        case 'allTest':
                            $users->where('users.role', '<', 0);
                            break;
                        default:
                            $users->where('users.role', $request->role);
                    }
                }

                $users = $users->orderBy('created_at', 'DESC')->paginate(100);

                return view('admin.users', [
                    'users' => $users,
                    'userTypes' => $userTypes,
                    'filterData' => $filterData,
                ]);
            case 3:
                return redirect('/admin/agent/list');

                break;
            case 10:
                return redirect('/admin/translations');
        }
    }

    public function settings()
    {
        return view('passwords');
    }

    public function confirmEmail(Request $request)
    {
        $user = Auth::user();

        if ($user->isConfirmed()) {
            return redirect()->back()->withErrors(['E-mail already confirmed']);
        }

        $date = Carbon::now();
        $date->modify('-1 minutes');

        $activation = UserActivation::where('updated_at', '>=', $date)
            ->where('user_id', $user->id)->first();

        if ($activation) {
            return redirect()->back()->withErrors(['Mail already sent. You can try in 15 minutes.']);
        }

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

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

        return redirect()->back()->with('popup', [
            'E-mail confirmation',
            //'Success',
            'We sent you confirmation link. Check your mail please.',
        ]);
    }

    public function activate($token, $email)
    {
        $linkActiveConfirm = config('appAdditional.linkActiveConfirm');

        $date = Carbon::now();
        $date->modify("-$linkActiveConfirm day");

        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return redirect('/')->withErrors(
                ['Email wasn\'t confirmed. Invalid email.']);
        }

        if ($user->isConfirmed()) {
            return redirect('/')->withErrors(['Email already confirmed']);
        }

        $activation = UserActivation::where('user_id', $user->id)
            ->where('token', $token)->where('updated_at', '>=', $date)->first();

        if ($activation) {
            $activation->activated = 1;
            $activation->save();

            $user->email_confirmed = 1;
            $user->save();

            $mail = new BaseMailable('emails.congratulations', ['email' => $user->email]);
            $mail->subject('Email is now validated');
            Mail::to($user)->send($mail);

            return redirect('/')->with('popup',
                [
                    'E-mail confirmation',
                    //'Success',
                    'Congratulations! E-mail was confirmed!',
                    'confirm_email'
                ]);
        } else {
            return redirect('/')->withErrors(
                ['Email wasn\'t confirmed. Invalid link.']);
        }
    }

    public function password(Request $request)
    {
        $errors = [];

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            $errors[] = 'Wrong password';
        }

        if ($request->input('password') != $request->input('password_confirmation')) {
            $errors[] = 'Passwords not match';
        }

        if (strlen($request->input('password')) < 6) {
            $errors[] = 'Too easy password';
        }

        if (count($errors) > 0) {
            return redirect()->back()->withErrors($errors);
        }

        Auth::user()->password = Hash::make($request->input('password'));
        Auth::user()->save();

        return redirect()->back()->with('popup', ['Success', 'Change password', 'Pasword was changed!']);
    }

    public function update(Request $request, User $user)
    {
        //to do check this method
        $configUser = config('appAdditional.users');
        $userTypes = $configUser['roles'];
        $userTypes = array_filter($userTypes, function ($item) {
            return !(boolean)$item['noEdit'];
        });

        if ($request->filled('role')) {
            $requestRole = (int) $request->input('role');
            if (array_search($requestRole, array_column($userTypes, 'key'), true) === false) {
                return redirect()->back()->withErrors(['Invalid role']);
            }
            //validation

            $commission = $request->input('commission');

            if (!is_numeric($commission)) {
                return redirect()->back()->withErrors(['Invalid commission']);
            }

            if ($commission < 0 or $commission > 100) {
                return redirect()->back()->withErrors(['Invalid commission']);
            }

            if ($request->input('role') == 0) {
                $commission = 0;
            }

            if ($request->input('confirmation_required') == 1) {
                $user->confirmation_required = 1;
            } else {
                $user->confirmation_required = 0;
            }

            //email confirm
            $emailConfirmed = ($request->filled('email_confirmed')) ? 1 : 0;
            $user->email_confirmed = $emailConfirmed;

            $user->commission = $commission;
            $user->role = $request->input('role');

            $user->save();
            if ($user->role == 1 or $user->role == 3) {
                $this->setAgentKoef($user, $commission);
            }

            //block user
            $block = ($request->filled('block')) ? 1 : 0;
            $blockUser = ModernExtraUsers::where('user_id', $user->id)
                ->where('code', 'block')->first();
            //might use update or create but i use this way
            if (is_null($blockUser)) {
                ModernExtraUsers::create([
                    'user_id' => $user->id,
                    'code' => 'block',
                    'value' => $block,
                ]);
                $oldStatus = 'open';
            } else {
                ModernExtraUsers::where('user_id', $user->id)
                    ->where('code', 'block')->update([
                        'value' => $block,
                    ]);
            }

            $oldStatus = $block ? 'open' : 'block';
            $newStatus = $block ? 'block' : 'open';

            event(new AccountStatusEvent($user, $oldStatus, $newStatus));

            if ($block === 1) {
                //to do necessary update this code for all drivers etc
                //to do middleware where mark  active
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
            }
        }

        return redirect()->back()->with('msg', 'User was updated!');
    }

    public function edit(User $user)
    {
    }

    protected function setAgentKoef($partner, $koef)
    {
        $newKoef = AgentsKoef::where('user_id', $partner->id)->where('created_at', '>', date('Y-m-d'))->first();
        if (!$newKoef) {
            $newKoef = new AgentsKoef();
            $newKoef->user_id = $partner->id;
        }
        $newKoef->koef = $koef;
        $newKoef->save();
        $partner->commission = $koef;
        $partner->save();
    }
}
