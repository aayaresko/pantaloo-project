<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use App\Tracker;
use App\Models\StatisticalData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserToAgent extends CommonMiddleware
{
    /**
     * @var array
     */
    protected $except = [
        '/',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $shouldPassThrough = $this->shouldPassThrough($request);
        if ($shouldPassThrough) {
            return $next($request);
        }

        if (! Auth::check()) {
            if ($request->has('ref')) {
                $ref = $request->input('ref');
                $tracker = Tracker::where('ref', $ref)->first();
                if ($tracker) {

                    //set count for this enters
                    $appAdditional = config('appAdditional');
                    $eventStatistic = $appAdditional['eventStatistic'];

                    StatisticalData::create([
                        'event_id' => $eventStatistic['enter'],
                        'value' => 'enter',
                        'tracker_id' => $tracker->id,
                    ]);
                    //set count for this enters

                    Cookie::queue('tracker_id', $tracker->id, 60 * 24 * 30);
                }
            }
        }

        return $next($request);
    }
}
