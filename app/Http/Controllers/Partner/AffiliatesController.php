<?php

namespace App\Http\Controllers\Partner;

use DB;
use App\User;
use Validator;
use App\Banner;
use App\Tracker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StatisticalData;
use App\Models\Partners\Feedback;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

/**
 * Class AffiliatesController
 * @package App\Http\Controllers\Partner
 */
class AffiliatesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        //test to two auth
        if (Auth::check()) {
            if (Auth::user()->isAgent()) {
                return redirect()->route('agent.dashboard');
            }
        }
        return view('affiliates.lending');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trackers(Request $request)
    {
        $user = $request->user();
        $trackersFileds = ['id', 'ref', 'name'];
        $trackers = Tracker::select($trackersFileds)->where('user_id', $user->id)->get();

        $configPartner = config('partner');
        $necessaryAddress = config('app.foreignPages.main');
        $params['link'] = sprintf("%s?%s=", $necessaryAddress,
            $configPartner['keyLink']);

        return view('affiliates.trackers', [
            'trackers' => $trackers,
            'params' => $params
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function marketingMaterial($id)
    {
        $params = [];
        $configPartner = config('partner');
        $bannersFileds = ['id', 'url'];
        $banners = Banner::select($bannersFileds)->get();
        $trackersFileds = ['id', 'ref', 'name'];
        $tracker = Tracker::select($trackersFileds)->where('id', $id)->first();
        $params['name'] = $tracker->name;

        $necessaryAddress = config('app.foreignPages.main');
        $params['link'] = sprintf("%s?%s=%s", $necessaryAddress,
            $configPartner['keyLink'], $tracker->ref);

        $banners->map(function ($item, $key) use ($params) {
            $item->link = $params['link'];

            $item->html = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $item->url,
                'name' => $params['name'],
                'style' => '',
            ]);

            $item->htmlView = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $item->url,
                'name' => $params['name'],
                'style' => "style=max-height:100px",
            ]);

            return $item;
        });

        return view('affiliates.marketing_material')->with([
            'banners' => $banners
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'name' => 'required|string|min:3|max:60',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $errors = [];
        if ($validator->fails()) {
            $validatorErrors = $validator->errors()->toArray();
            array_walk_recursive($validatorErrors, function ($item, $key) use (&$errors) {
                array_push($errors, $item);
            });

            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => $errors,
                    'title' => 'Error',
                    'body' => (string)view('affiliates.parts.body')->with(['data' => $errors])
                ]
            ]);
        }

        //might add try - catch and transaction
        Feedback::create($request->toArray());
        return response()->json([
            'status' => true,
            'message' => [
                'title' => 'Info',
                'body' => (string)view('affiliates.parts.body')->with(['data' => 'We will contact you shortly'])
            ]
        ]);
    }

    /**
     *
     * in future - will need rewrite this method
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        try {
            $from = Carbon::createFromFormat("Y-m-d", $request->input('start'));
        } catch (\Exception $e) {
            $from = Carbon::now();
        }

        try {
            $to = Carbon::createFromFormat("Y-m-d", $request->input('end'));
        } catch (\Exception $e) {
            $to = Carbon::now();
        }

        $to->setTime(23, 59, 59);
        $from->setTime(0, 0, 0);

        $transactions = collect();

        $currentUser = Auth::user();
        $users = User::where('agent_id', $currentUser->id)->get();

        $result = collect();

        foreach ($users as $user) {
            $stat = $user->stat($from, $to);

            foreach ($stat as $key => $value)
                $stat[$key] = round($value, 2);

            $stat['user'] = $user;

            $result->push($stat);
        }

        $trackers = collect();

        $appAdditional = config('appAdditional');
        $eventStatistic = $appAdditional['eventStatistic'];
        $eventEnterId = $eventStatistic['enter'];
        $eventRegistrId = $eventStatistic['register'];

        $trackerAll = Tracker::select([
            '*',
            DB::raw("(SELECT count(*) FROM statistical_data where tracker_id = trackers.id and " .
                "created_at >= '$from' and created_at <= '$to' and event_id = '$eventEnterId') as enter"),
            DB::raw("(SELECT count(*) FROM statistical_data where tracker_id = trackers.id and " .
                "created_at >= '$from' and created_at <= '$to' and event_id = '$eventRegistrId') as register"),
        ])->get();

        foreach ($trackerAll as $tracker) {
            $stat = $tracker->stat($from, $to);

            $stat['tracker'] = $tracker->name;

            $stat['enters'] = $tracker->enter;
            $stat['registrations'] = $tracker->register;

            $trackers->push($stat);
        }

        $data = [
            'users' => $result,
            'trackers' => $trackers,
            'deposit_total' => $result->sum('deposits'),
            'bonus_total' => $result->sum('bonus'),
            'revenue_total' => $result->sum('revenue'),
            'profit_total' => $result->sum('profit')
        ];

        return view('affiliates.dashboard', $data);
    }
}
