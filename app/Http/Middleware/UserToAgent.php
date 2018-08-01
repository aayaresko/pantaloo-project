<?php

namespace App\Http\Middleware;

use App\Tracker;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserToAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check())
        {
            if($request->has('ref'))
            {
                $tracker = Tracker::where('ref', $request->input('ref'))->first();

                if($tracker)
                {
                    Cookie::queue('tracker_id', $tracker->id, 60*24*30);
                }
            }
        }

        return $next($request);
    }
}
