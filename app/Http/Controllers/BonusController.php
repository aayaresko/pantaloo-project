<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Bonus;
use App\UserBonus;
use App\Http\Requests;
use Helpers\BonusHelper;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    public function index()
    {
        //to do if bonus be activated for this user
        $bonuses = Bonus::where('public', 1)->orderBy('rating', 'desc')->get();

        //check bonus
        $bonuses = $bonuses->filter(function ($item) {
            $bonusClass = $item->getClass();
            $bonusObject = new $bonusClass(Auth::user());
            //check
            return $bonusObject->bonusAvailable();
        });

        $active_bonus = Auth::user()->bonuses()->first();

        $bonusStatistics = null;
        if ($active_bonus) {
            $bonusStatistics = BonusHelper::bonusStatistics($active_bonus);
        }

        return view('bonus', [
            'bonuses' => $bonuses,
            'active_bonus' => $active_bonus,
            'bonusStatistics' => $bonusStatistics,
        ]);
    }

    public function activate(Bonus $bonus)
    {
        //get user by request
        $userRequest = Auth::user();

        //to do - check this - and edit this way
        if (! $bonus->public) {
            return redirect()->back()->withErrors(['No access']);
        }

        DB::beginTransaction();

        $class = BonusHelper::getClass($bonus->id);

        $user = User::where('id', $userRequest->id)->lockForUpdate()->first();

        $bonusObj = new $class($user);

        $bonusActivate = $bonusObj->activate();

        if ($bonusActivate['success'] === false) {
            DB::rollBack();
            redirect()->back()->withErrors([$bonusActivate['message']]);
        }

        DB::commit();

        return redirect()->back()->with('popup',
            ['BONUS', 'Bonus was activated!', 'Bonus was successfully activated!']);

//        //to do - check this - and edit this way
//        if (!$bonus->public) {
//            return redirect()->back()->withErrors(['No access']);
//        }
//
//        $user = Auth::user();
//
//        $class = $bonus->getClass();
//
//        $bonus_obj = new $class($user);
//
//
//        DB::beginTransaction();
//
//        $bonusActivate = $bonus_obj->activate();
//
//        if ($bonusActivate['success'] === false) {
//            DB::rollBack();
//            redirect()->back()->withErrors([$bonusActivate['message']]);
//        }
//
//        DB::commit();
//
//        return redirect()->back()->with('popup',
//            ['BONUS', 'Bonus was activated!', 'Bonus was successfully activated!']);
    }

    public function cancel()
    {
        $user_bonus = Auth::user()->bonuses()->first();

        if (! $user_bonus) {
            return redirect()->back();
        }
        $class = $user_bonus->bonus->getClass();
        $bonus_obj = new $class(Auth::user());

        DB::beginTransaction();
        $bonusClose = $bonus_obj->cancel('Closed by user');

        if ($bonusClose['success'] === false) {
            DB::rollBack();
            redirect()->back()->withErrors([$bonusClose['message']]);
        }
        DB::commit();

        return redirect()->back();
    }

    /*
     * ADMIN PANEL
     *
     */
    public function userBonuses(User $user)
    {
        //FIX THIS METHOD
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
            'bonus_obj' => $bonus_obj,
        ]);
    }

    public function adminActivate(User $user, Bonus $bonus)
    {
        //TO DO FIX THIS
        $class = $bonus->getClass();
        $bonusObject = new $class($user);

        DB::beginTransaction();
        $bonusActivate = $bonusObject->activate(['mode' => 1]);
        if ($bonusActivate['success'] === false) {
            DB::rollBack();

            return redirect()->back()->withErrors([$bonusActivate['message']]);
        }
        DB::commit();

        return redirect()->back()->with('msg', 'Bonus was activated!');
    }

    public function adminCancel(User $user)
    {
        $userBonus = $user->bonuses()->first();

        if (! $userBonus) {
            return redirect()->back();
        }

        $class = BonusHelper::getClass($user->bonus_id);
        $bonusObject = new $class($user);

        DB::beginTransaction();
        $bonusCancel = $bonusObject->cancel('Closed by admin');
        if ($bonusCancel['success'] === false) {
            DB::rollBack();

            return redirect()->back()->withErrors([$bonusCancel['message']]);
        }
        DB::commit();

        return redirect()->back()->with('msg', 'Bonus was canceled');
    }

    public function promo(Request $request)
    {
        $user = $request->user();

        $activeBonus = null;
        if (! is_null($user)) {
            $activeBonus = UserBonus::select('bonus_id')->where('user_id', $user->id)->first();
        }

        return view('bonuses')->with(['activeBonus' => $activeBonus]);
    }
}
