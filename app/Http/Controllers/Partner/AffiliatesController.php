<?php

namespace App\Http\Controllers\Partner;

use Validator;
use App\Banner;
use App\Tracker;
use Illuminate\Http\Request;
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
        return view('affiliates.trackers', ['trackers' => $trackers]);
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
        $params['link'] = sprintf("%s?%s=%s", url('/'), $configPartner['keyLink'], $tracker->ref);

        $banners->map(function ($item, $key) use ($params) {
            $item->link = $params['link'];

            $item->html = view('affiliates.parts.banner_html', [
                'link' => $params['link'],
                'image' => $item->url,
                'name' => $params['name']
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
}
