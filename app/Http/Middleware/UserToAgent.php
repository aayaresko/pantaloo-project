<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use App\Tracker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserToAgent
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
        if (!Auth::check()) {
            if ($request->has('ref')) {
                $ref = $request->input('ref');
                $tracker = Tracker::where('ref', $ref)->first();

                if ($tracker) {
                    Tracker::where('ref', $ref)->update([
                        'link_clicks' => DB::raw('link_clicks + 1')
                    ]);
                    //set count for this enters
                    Cookie::queue('tracker_id', $tracker->id, 60 * 24 * 30);
                }
            }
        }

        return $next($request);
    }
}
