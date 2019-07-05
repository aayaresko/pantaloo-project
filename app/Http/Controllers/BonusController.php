<?php

namespace App\Http\Controllers;

use DB;
use Cookie;
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
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = is_null($user) ? null : $user->id;

        $bonuses = Bonus::with(['activeBonus' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('rating', 'desc')->get();

        $bonusForView = [];
        $activeBonus = null;
        foreach ($bonuses as $bonus) {
            $bonusClass = BonusHelper::getClass($bonus->id);
            $bonusObject = new $bonusClass($user);
            $bonusAvailable = $bonusObject->bonusAvailable(['mode' => 0]);

            if (!is_null($bonus->activeBonus)) {
                $activeBonus = $bonus;
                $bonusStatistics = BonusHelper::bonusStatistics($bonus->activeBonus);
                $activeBonus->bonusStatistics = $bonusStatistics;
            }

            if ($bonusAvailable) {
                array_push($bonusForView, $bonus);
            }
        }

        return view('bonus', [
            'bonusForView' => $bonusForView,
            'activeBonus' => $activeBonus
        ]);
    }

    public function promo(Request $request)
    {
//        $user = $request->user();
//        $userId = is_null($user) ? null : $user->id;
//
//        $bonuses = Bonus::with(['activeBonus' => function ($query) use ($userId) {
//            $query->where('user_id', $userId);
//        }])->orderBy('rating', 'desc')->get();
//
//        $bonusForView = [];
//        foreach ($bonuses as $bonus) {
//            $bonusClass = BonusHelper::getClass($bonus->id);
//            $bonusObject = new $bonusClass($user);
//            $bonusAvailable = $bonusObject->bonusAvailable(['mode' => 1]);
//
//            if ($bonusAvailable or !is_null($bonus->activeBonus)) {
//                array_push($bonusForView, $bonus);
//            }
//        }

        $user = $request->user();
        $userId = is_null($user) ? null : $user->id;

        $bonuses = Bonus::with(['activeBonus' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('rating', 'desc')->get();

        $bonusForView = [];

        //get only availiable bonus
        foreach ($bonuses as $key => $bonus) {
            $bonusClass = BonusHelper::getClass($bonus->id);
            $bonusObject = new $bonusClass($user);
            $bonusAvailable = $bonusObject->bonusAvailable(['mode' => 1]);
            if (!$bonusAvailable) {
                unset($bonuses[$key]);
            }
        }

        //get status bonuses
        foreach ($bonuses as $bonus) {
            $bonusClass = BonusHelper::getClass($bonus->id);
            $bonusObject = new $bonusClass($user);
            $bonusAvailable = $bonusObject->bonusAvailable(['mode' => 0]);

            $bonus->notAvailable = true;
            if (!$bonusAvailable) {
                $bonus->notAvailable = false;
            }

            if (!is_null($bonus->activeBonus)) {
                $bonusStatistics = BonusHelper::bonusStatistics($bonus->activeBonus);
                $bonus->bonusStatistics = $bonusStatistics;
            }

            array_push($bonusForView, $bonus);
        }

//        $currencyCode = config('app.currencyCode');

//        return view('bonus', [
//            'currencyCode' => $currencyCode,
//            'bonusForView' => $bonusForView,
//            'user' => $user
//        ]);


        return view('bonuses', [
            'bonusForView' => $bonusForView,
//            'currencyCode' => $currencyCode,
//            'user' => $user
        ]);
    }

    public function activate(Bonus $bonus)
    {
        //get user by request
        $userRequest = Auth::user();

//        //to do - check this - and edit this way
//        if (!$bonus->public) {
//            return redirect()->back()->withErrors(['No access']);
//        }

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

        if (!$user_bonus) {
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

        if (!$userBonus) {
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


    public function getWelcomeBonus(Request $request)
    {
        $user = $request->user();
        if (is_null($user)) {
            $configBonusAccess = config('bonus.setWelcomeBonus');
            $name = $configBonusAccess['name'];
            $minutes = $configBonusAccess['time'];
            $value = $configBonusAccess['value'];
            Cookie::queue($name, $value, $minutes);
        }
        return redirect('/');
    }
}
