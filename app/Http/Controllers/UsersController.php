<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Jobs\SetUserCountry;
use App\UserActivation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if (Gate::allows('accessUserAdmin')) {
            $users = User::orderBy('created_at', 'DESC')->get();
            return view('admin.users', ['users' => $users]);
        } else {
            return redirect('admin/translations');
        }
    }

    public function settings()
    {
        return view('passwords');
    }

    public function confirmEmail(Request $request)
    {
        $user = Auth::user();

        if($user->isConfirmed()) return redirect()->back()->withErrors(['E-mail already confirmed']);

        $date = Carbon::now();
        $date->modify('-1 minutes');

        $activation = UserActivation::where('updated_at', '>=', $date)->where('user_id', $user->id)->first();

        if($activation)
            return redirect()->back()->withErrors(['Mail already sent. You can try in 15 minutes.']);

        $lang = Config::get('lang');

        $template = 'emails.' . $lang . '.confirm';

        $domain = Domain::where('lang', $lang)->first();

        if(!$domain) $domain = 'www.casinobit.co';

        $token = hash_hmac('sha256', str_random(40), config('app.key'));

        $link = 'https://' . $domain->domain . '/activate/' . $token;

        $activation = UserActivation::where('user_id', $user->id)->first();

        if(!$activation) $activation = new UserActivation();

        $activation->user()->associate($user);
        $activation->token = $token;
        $activation->activated = 0;

        $activation->save();

        Mail::queue('emails.confirm', ['link' => $link], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Confirm email');
        });

        return redirect()->back()->with('popup', [
            'E-mail confirmation',
            'Success',
            'We sent you confirmation link. Check your mail please.',
        ]);
    }

    public function activate($token)
    {
        $date = Carbon::now();
        $date->modify('-1 day');

        $user = Auth::user();

        if($user->isConfirmed()) return redirect('/')->withErrors(['Email already confirmed']);

        $activation = UserActivation::where('user_id', $user->id)->where('token', $token)->where('updated_at', '>=', $date)->first();

        if($activation)
        {
            $activation->activated = 1;
            $activation->save();

            $user->email_confirmed = 1;
            $user->save();

            Mail::queue('emails.congratulations', ['email' => $user->email], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Email is now validated');
            });

            return redirect('/')->with('popup', ['E-mail confirmation', 'Success', 'Congratulations! E-mail was confirmed!']);
        }
        else
        {
            return redirect('/')->withErrors(['Email wasn\'t confirmed. Invalid link.']);
        }
    }

    public function password(Request $request)
    {
        $errors = [];

        if(!Hash::check($request->old_password, Auth::user()->password)) $errors[] = 'Wrong password';
        if($request->input('password') != $request->input('password_confirmation')) $errors[] = 'Passwords not match';
        if(strlen($request->input('password')) < 6) $errors[] = 'Too easy password';

        if(count($errors) > 0) return redirect()->back()->withErrors($errors);

        Auth::user()->password = Hash::make($request->input('password'));
        Auth::user()->save();

        return redirect()->back()->with('popup', ['Success', 'Change password', 'Pasword was changed!']);
    }

    public function update(Request $request, User $user)
    {
        if($request->has('role'))
        {
            if($request->input('role') != 1 and $request->input('role') != 0) return redirect()->back()->withErrors(['Invalid role']);

            $commission = $request->input('commission');

            if(!is_numeric($commission)) return redirect()->back()->withErrors(['Invalid commission']);

            if($commission < 0 or $commission > 100)  return redirect()->back()->withErrors(['Invalid commission']);

            if($request->input('role') == 0) $commission = 0;

            if($request->input('confirmation_required') == 1) $user->confirmation_required = 1;
            else $user->confirmation_required = 0;

            $user->commission = $commission;
            $user->role = $request->input('role');

            $user->save();
        }

        return redirect()->back()->with('msg', 'User was updated!');
    }

    public function edit(User $user)
    {

    }
}
