<?php

namespace App\Http\Controllers\Partner;

use App\Models\AgentsKoef;
use DB;
use App\User;
use Validator;
use App\Banner;
use App\Tracker;
use Carbon\Carbon;
use App\ExtraUser;
use App\Transaction;
use Helpers\GeneralHelper;
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
    const PLAYER_ROLE = 0;
    const AGENT_ROLE = 1;
    const GLOBAL_AGENT_ROLE = 4;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        //test to two auth
        if (Auth::check()) {
            if (Auth::user()->role == self::GLOBAL_AGENT_ROLE) {
                return redirect()->route('admin.agents.tree');
            }
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
        $trackers = Tracker::select($trackersFileds)->where('user_id', $user->id)->get();

        $configPartner = config('partner');
        $necessaryAddress = config('app.foreignPages.main');
        $ref = false;

        foreach ($trackers as $tracker) {
            $tracker->campaign_linkFull = $tracker->campaign_link;
            if (is_null($tracker->campaign_link)) {
                $tracker->campaign_linkFull = $necessaryAddress;
                $ref = $tracker->ref;
            }

            $tracker->fullLink = sprintf("%s?%s=%s", $tracker->campaign_linkFull,
                $configPartner['keyLink'], $tracker->ref);
        }
        if (!$ref) {
            $ref = str_random(12);
            $newTracker = new Tracker();
            $newTracker->user_id = $user->id;
            $newTracker->name = 'default';
            $newTracker->campaign_link = config('partner.main_url');
            $newTracker->ref = $ref;
            $newTracker->save();
        }

        return view('affiliates.trackers', [
            'trackers' => $trackers,
            'ref' => $ref
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

        $params['link'] = sprintf("%s?%s=%s", $tracker->campaign_linkFull,
            $configPartner['keyLink'], $tracker->ref);

        $url = url('/');
        $params['url'] = $url;
        $banners->map(function ($item, $key) use ($params) {
            $item->link = $params['link'];

            $item->html = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $params['url'] . $item->url,
                'name' => $params['name'],
                'style' => '',
            ]);

            $item->htmlView = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $params['url'] . $item->url,
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

        //act
        $currentUser = Auth::user();
        //preparation
        $currencyCode = config('app.currencyCode');
        $cpumBtcLimit = config('appAdditional.defaultmBtcCpu');
        $cpaCurrencyCode = config('appAdditional.cpaCurrencyCode');

        $extraUser = ExtraUser::where('user_id', $currentUser->id)->first();
        if (!is_null($extraUser)) {
            $cpumBtcLimit = $extraUser->base_line_cpa;
        }

        $typeDeposit = 3;
        $users = User::select([
            '*',
//            DB::raw("(SELECT sum(transactions.sum) FROM transactions where user_id = users.id and " .
//                "type = $typeDeposit and created_at >= '$from' and created_at <= '$to') as cpu"),
        ])->where('agent_id', $currentUser->id)->get();


        $result = collect();
        foreach ($users as $user) {
            $stat = $user->stat($from, $to);
            //set cpa
            foreach ($stat as $key => $value)
                $stat[$key] = round($value, 2);

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
            DB::raw("(SELECT count(*) FROM statistical_data where tracker_id = trackers.id and " .
                "created_at >= '$from' and created_at <= '$to' and event_id = '$eventEnterId') as enter"),
            DB::raw("(SELECT count(*) FROM statistical_data where tracker_id = trackers.id and " .
                "created_at >= '$from' and created_at <= '$to' and event_id = '$eventRegistrId') as register"),
        ])->where('user_id', $currentUser->id)->get();

        foreach ($trackerAll as $tracker) {
            $stat = $tracker->stat($from, $to);

            $stat['tracker'] = $tracker->name;

            $stat['enters'] = $tracker->enter;
            $stat['registrations'] = $tracker->register;

            $trackers->push($stat);
        }
        $countries = false;
        if ($currentUser->role == 3) {
            $countriesCode = $currentUser->affiliateCountries->pluck('name')->toArray();
            foreach ($countriesCode as $countryCode) {
                $countries .= $countryCode . ' ';
            }
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
            'countries' => $countries
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
            ->where('type', 4)->get();

        return view('affiliates.withdraw', [
            'available' => $available,
            'transactions' => $transactions,
            'statusPayment' => $statusPayment
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function partners()
    {
        $affiliates = User::where('agent_id', Auth::user()->id)->where('role', self::AGENT_ROLE)->with('koefs', 'benefits')->get();
        $myKoef = Auth::user()->koefs->koef;

        return view('affiliates.partners', compact('affiliates', 'myKoef'));
    }

    public function changeKoef($id, Request $request)
    {
        $partner = User::where('agent_id', Auth::user()->id)->where('role', self::AGENT_ROLE)->where('id', $id)->firstOrFail();
        $newKoef = AgentsKoef::where('user_id', $partner->id)->where('created_at', '>', date('Y-m-d'))->first();
        if (!$newKoef) {
            $newKoef = new AgentsKoef();
            $newKoef->user_id = $partner->id;
        }
        $newKoef->koef = $request->koef;
        $newKoef->save();
        $partner->commission = $request->koef;
        $partner->save();

        return back();
    }

    public function users()
    {
        $users = User::where('agent_id', Auth::user()->id)->with('countries')->where('role', self::PLAYER_ROLE)->get();
        $myKoef = Auth::user()->koefs->koef;

        return view('affiliates.users', compact('myKoef', 'users'));
    }

    public function partnerShow($id, User $user)
    {
        $partner = $user->findOrFail($id);
        if (Auth::user()->id == $partner->agent_id) {
            $users = $user->where('agent_id', $id)->with('countries')->where('role', self::PLAYER_ROLE)->get();
            $affiliates = $user->where('agent_id', $id)->where('role', self::AGENT_ROLE)->with('koefs', 'benefits')->get();

            return view('affiliates.partner', compact('partner', 'users', 'affiliates'));
        }

        abort(403);
    }
}
