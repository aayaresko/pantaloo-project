<?php

namespace App\Http\Controllers;

use App\Mail\BaseMailable;
use App\Mail\EmailConfirm;
use DB;
use App\User;
use App\Domain;
use Carbon\Carbon;
use App\Http\Requests;
use Mockery\Exception;
use App\UserActivation;
use App\ModernExtraUsers;
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
                //to do block to config value
                $users = User::select(['users.*', DB::raw('IFNULL(block.value, 0) as block')])
                    ->leftJoin('modern_extra_users as block', function ($join) {
                        $join->on('users.id', '=', 'block.user_id')
                            ->where('block.code', '=', 'block');
                    });
                if ($request->email) {
                    $users->where('users.email', $request->email);
                }

                $users = $users->orderBy('created_at', 'DESC')->paginate(100);

                return view('admin.users', ['users' => $users]);
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

        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        $link = url('/').'/activate/'.$token.'/email/'.$user->email;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if (! $activation) {
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
            'Success',
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

            Mail::queue('emails.congratulations', ['email' => $user->email], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Email is now validated');
            });

            return redirect('/')->with('popup',
                ['E-mail confirmation', 'Success', 'Congratulations! E-mail was confirmed!']);
        } else {
            return redirect('/')->withErrors(
                ['Email wasn\'t confirmed. Invalid link.']);
        }
    }

    public function password(Request $request)
    {
        $errors = [];

        if (! Hash::check($request->old_password, Auth::user()->password)) {
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
        if ($request->has('role')) {
            if ($request->input('role') != 1 and $request->input('role') != 0) {
                return redirect()->back()->withErrors(['Invalid role']);
            }
            //validation

            $commission = $request->input('commission');

            if (! is_numeric($commission)) {
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
            $emailConfirmed = ($request->has('email_confirmed')) ? 1 : 0;
            $user->email_confirmed = $emailConfirmed;

            $user->commission = $commission;
            $user->role = $request->input('role');

            $user->save();

            //block user
            $block = ($request->has('block')) ? 1 : 0;
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
                $sessionsUser = DB::table('sessions')
                    ->select(['id'])
                    ->where('user_id', $user->id)
                    ->pluck('id')->all();

                DB::table('sessions')->whereIn('id', $sessionsUser)->delete();
            }
        }

        return redirect()->back()->with('msg', 'User was updated!');
    }

    public function edit(User $user)
    {
    }
}
