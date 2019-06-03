<?php

namespace App\Http\Controllers\Partner;

use DB;
use App\User;
use Validator;
use App\Banner;
use App\Tracker;
use App\ExtraUser;
use Carbon\Carbon;
use App\Transaction;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Models\StatisticalData;
use App\Models\Partners\Feedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class AffiliatesController.
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
        $trackersFileds = ['id', 'ref', 'name', 'campaign_link'];
        $trackers = Tracker::select($trackersFileds)->where('user_id', $user->id)->get()->all();

        $configPartner = config('partner');
        $necessaryAddress = config('app.foreignPages.main');

        foreach ($trackers as $tracker) {
            $tracker->campaign_linkFull = $tracker->campaign_link;
            if (is_null($tracker->campaign_link)) {
                $tracker->campaign_linkFull = $necessaryAddress;
            }

            $tracker->fullLink = sprintf('%s?%s=%s', $tracker->campaign_linkFull,
                $configPartner['keyLink'], $tracker->ref);
        }

        return view('affiliates.trackers', [
            'trackers' => $trackers,
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
        $banners = Banner::select($bannersFileds)->get()->all();
        $trackersFileds = ['id', 'ref', 'name', 'campaign_link'];
        $tracker = Tracker::select($trackersFileds)->where('id', $id)->first();
        $params['name'] = $tracker->name;
        $params['campaign_link'] = $tracker->campaign_link;

        $necessaryAddress = config('app.foreignPages.main');
        //dd($necessaryAddress);
        $tracker->campaign_linkFull = $tracker->campaign_link;
        if (is_null($tracker->campaign_link)) {
            $tracker->campaign_linkFull = $necessaryAddress;
        }

        $params['link'] = sprintf('%s?%s=%s', $tracker->campaign_linkFull,
            $configPartner['keyLink'], $tracker->ref);

        $url = url('/');
        $params['url'] = $url;
        $banners->map(function ($item, $key) use ($params) {
            $item->link = $params['link'];

            $item->html = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $params['url'].$item->url,
                'name' => $params['name'],
                'style' => '',
            ]);

            $item->htmlView = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $params['url'].$item->url,
                'name' => $params['name'],
                'style' => 'style=max-height:100px',
            ]);

            return $item;
        });

        return view('affiliates.marketing_material')->with([
            'banners' => $banners,
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
                    'body' => (string) view('affiliates.parts.body')->with(['data' => $errors]),
                ],
            ]);
        }

        //might add try - catch and transaction
        Feedback::create($request->toArray());

        return response()->json([
            'status' => true,
            'message' => [
                'title' => 'Info',
                'body' => (string) view('affiliates.parts.body')->with(['data' => 'We will contact you shortly']),
            ],
        ]);
    }

    /**
     * in future - will need rewrite this method.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        try {
            $from = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        } catch (\Exception $e) {
            $from = Carbon::now();
        }

        try {
            $to = Carbon::createFromFormat('Y-m-d', $request->input('end'));
        } catch (\Exception $e) {
            $to = Carbon::now();
        }

        $to->setTime(23, 59, 59);
        $from->setTime(0, 0, 0);

        //act
        $currentUser = Auth::user();
        //preparation
        $currencyCode = config('app.currencyCode');
        $cpumBtcLimit = config('appAdditional.defaultmBtcCpu');
        $cpaCurrencyCode = config('appAdditional.cpaCurrencyCode');

        $extraUser = ExtraUser::where('user_id', $currentUser->id)->first();
        if (! is_null($extraUser)) {
            $cpumBtcLimit = $extraUser->base_line_cpa;
        }

        $typeDeposit = 3;
        $users = User::select([
            '*',
//            DB::raw("(SELECT sum(transactions.sum) FROM transactions where user_id = users.id and " .
//                "type = $typeDeposit and created_at >= '$from' and created_at <= '$to') as cpu"),
        ])->where('agent_id', $currentUser->id)->get()->all();

        $result = collect();
        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            //set cpa
            foreach ($stat as $key => $value) {
                $stat[$key] = round($value, 2);
            }

            $stat['cpa'] = ($stat['confirm_deposits'] >= $cpumBtcLimit) ? 1 : 0;
            $cpaPending = GeneralHelper::formatAmount($cpumBtcLimit - $stat['deposits']);
            $stat['cpaPending'] = ($cpaPending >= 0) ? $cpaPending : 0;

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
            DB::raw('(SELECT count(*) FROM statistical_data where tracker_id = trackers.id and '.
                "created_at >= '$from' and created_at <= '$to' and event_id = '$eventEnterId') as enter"),
            DB::raw('(SELECT count(*) FROM statistical_data where tracker_id = trackers.id and '.
                "created_at >= '$from' and created_at <= '$to' and event_id = '$eventRegistrId') as register"),
        ])->where('user_id', $currentUser->id)->get()->all();

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
            'pending_deposits' => $result->sum('pending_deposits'),
            'confirm_deposits' => $result->sum('confirm_deposits'),
            'bonus_total' => $result->sum('bonus'),
            'revenue_total' => $result->sum('revenue'),
            'profit_total' => $result->sum('profit'),
            'cpa_total' => $result->sum('cpa'),
            'cpaCurrencyCode' => $cpaCurrencyCode,
            'currencyCode' => $currencyCode,
        ];

        return view('affiliates.dashboard', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdraw(Request $request)
    {
        $statusPayment = config('appAdditional.statusPayment');
        $user = $request->user();
        $available = $user->getAgentAvailable();
        //get transaction withdraw
        //to do pagination for transactions
        $transactions = Transaction::where('user_id', $user->id)
            ->where('type', 4)->get()->all();

        return view('affiliates.withdraw', [
            'available' => $available,
            'transactions' => $transactions,
            'statusPayment' => $statusPayment,
        ]);
    }
}
