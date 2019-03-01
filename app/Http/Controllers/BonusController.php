<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    public function index()
    {
        //to do if bounus be actived for this user
        $bonuses = Bonus::where('public', 1)->orderBy('rating', 'desc')->get();

        //check bonus
        $bonuses = $bonuses->filter(function ($item) {
            $bonusClass = $item->getClass();
            $bonusObject = new $bonusClass(Auth::user());
            //check
            return $bonusObject->bonusAvailable();
        });

        $active_bonus = Auth::user()->bonuses()->first();

        if ($active_bonus) {
            $class = $active_bonus->bonus->getClass();
            $bonus_obj = new $class(Auth::user());
        } else {
            $bonus_obj = false;
        }

        return view('bonus', [
            'bonuses' => $bonuses,
            'active_bonus' => $active_bonus,
            'bonus_obj' => $bonus_obj
        ]);
    }

    public function activate(Bonus $bonus)
    {
        //to do - check this - and edit this way
        if (!$bonus->public) {
            return redirect()->back()->withErrors(['No access']);
        }

        $user = Auth::user();

        $class = $bonus->getClass();

        $bonus_obj = new $class($user);

        try {
            $bonus_obj->activate();
        } catch (\Exception $e) {
           return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->back()->with('popup',
            ['BONUS', 'Bonus was activated!', 'Bonus was successfully activated!']);
    }

    public function cancel()
    {
        $user_bonus = Auth::user()->bonuses()->first();

        if (!$user_bonus) {
            return redirect()->back();
        }
        $class = $user_bonus->bonus->getClass();
        $bonus_obj = new $class(Auth::user());

        try {
            $bonus_obj->cancel('Closed by user');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->back();
    }

    public function userBonuses(User $user)
    {
        $bonuses = Bonus::all();

        $active_bonus = $user->bonuses()->first();

        if ($active_bonus) {
            $class = $active_bonus->bonus->getClass();
            $bonus_obj = new $class($user);
        } else {
            $bonus_obj = false;
        }

        return view('admin.userBonuses', [
            'user' => $user,
            'bonuses' => $bonuses,
            'active_bonus' => $active_bonus,
            'bonus_obj' => $bonus_obj
        ]);
    }

    public function adminActivate(User $user, Bonus $bonus)
    {
        $class = $bonus->getClass();
        $bonus_obj = new $class($user);

        try {
            $bonus_obj->activate();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->back()->with('msg', 'Bonus was activated!');
    }

    public function adminCancel(User $user)
    {
        $user_bonus = $user->bonuses()->first();

        if (!$user_bonus) {
            return redirect()->back();
        }
        $class = $user_bonus->bonus->getClass();
        $bonus_obj = new $class($user);

        try {
            $bonus_obj->cancel('Closed by admin');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->back()->with('msg', 'Bonus was canceled');
    }

    public function promo()
    {
        return view('bonuses');
    }
}
