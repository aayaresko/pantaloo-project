<?php

namespace App\Http\Middleware;

use Closure;
use App\ExtraUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UsersOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            //update last activity
            $user->last_activity = Carbon::now();
            $user->save();

            //logout user if he is blocked.
            $extraUser = ExtraUser::where('user_id', $user->id)->first();
            if (!is_null($extraUser)) {
                if ((int)$extraUser->block > 0) {
                    Auth::logout();
                    return redirect('/');
                }
            }
        }
        return $next($request);
    }
}
