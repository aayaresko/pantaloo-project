<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Validator;
use App\Bonus;
use App\Transaction;
use App\Http\Requests;
use Helpers\BonusHelper;
use App\ModernExtraUsers;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdraw as WithdrawModel;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class UserAccountController extends Controller
{

    public function account(Request $request, $lang)
    {
        $user = $request->user();
        $currencyCode = config('app.currencyCode');

        $extraUser = ModernExtraUsers::where('user_id', $user->id)
            ->where('code', 'info')->first();

        return view('account', [
            'currencyCode' => $currencyCode,
            'extraUser' => $extraUser,
            'user' => $user,
            'lang' => $lang
        ]);
    }

    public function updateUserExtra(Request $request, $lang)
    {
        $user = $request->user();
        try {
            DB::beginTransaction();

            $errors = [];
            $data = $request->all();

            $validator = Validator::make($data, [
                'firstName' => 'required|string:3',
                'birthDay' => 'required|date',
                'countryCode' => 'required|exists:countries,code',
                'lastName' => 'string:3|nullable',
                'city' => 'string:1|nullable',
                'gender' => 'integer|in:1,2'
            ]);


            if ($validator->fails()) {
                $validatorErrors = $validator->errors()->toArray();
                array_walk_recursive($validatorErrors, function ($item) use (&$errors) {
                    array_push($errors, $item);
                });
                throw new \Exception('validation');
            }

            //main part
            $infoUser = [
                'name' => $request->firstName,
                'country' => $request->countryCode,
            ];

            User::where('id', $user->id)->update($infoUser);

            //second part
            $getExtraUser = ModernExtraUsers::where('user_id', $user->id)
                ->where('code', 'info')->first();

            $birthDay = $request->birthDay;

            $infoExtraUser = [
                'birthDay' => $birthDay,
            ];

            if ($request->filled('lastName')) {
                $infoExtraUser['lastName'] = $request->lastName;
            }

            if ($request->filled('city')) {
                $infoExtraUser['city'] = $request->city;
            }

            if ($request->filled('gender')) {
                $infoExtraUser['gender'] = (int)$request->gender;
            }

            if ($getExtraUser) {
                ModernExtraUsers::where('id', $getExtraUser->id)->update([
                    'value' => json_encode($infoExtraUser)
                ]);
            } else {
                ModernExtraUsers::create([
                    'user_id' => $user->id,
                    'code' => 'info',
                    'value' => json_encode($infoExtraUser)
                ]);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            if (empty($errors)) {
                $errors = [$ex->getMessage()];
            }

            return response()->json([
                'status' => false,
                'message' => [
                    'errors' => $errors
                ]
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => ['Update successful']
        ]);
    }

    public function deposit(Request $request, $lang)
    {
        $user = $request->user();
        $qrCodeClass = new BaconQrCodeGenerator;
        $qrCode = $qrCodeClass->size(200)->generate($user->bitcoin_address);
        $currencyCode = config('app.currencyCode');

        return view('deposit', [
            'currencyCode' => $currencyCode,
            'qrCode' => $qrCode,
            'user' => $user,
            'lang' => $lang
        ]);
    }

    public function getDeposits(Request $request)
    {
        $param = [];
        $user = $request->user();
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        try {
            if (is_null($user)) {
                throw new \Exception('user is not found');
            }

            $param['minConfirmBtc'] = $minConfirmBtc;

            $param['columns'] = [
                0 => 'created_at',
                1 => 'transaction_id',
                2 => 'confirmations',
                3 => 'value'
            ];

            $param['columnsAlias'] = [
                0 => 'created_at as date',
                1 => 'transaction_id as id',
                2 => 'confirmations',
                3 => 'value as amount'
            ];

            $param['whereCompare'] = [
                ['user_id', '=', $user->id]
            ];

            /* ACT */
            $whereCompare = $param['whereCompare'];

            $countSum = SystemNotification::select($param['columns'])->where($whereCompare)
                ->skip($request->startItem)->take($request->getItem)->get()->count();

            $totalData = $countSum;
            $totalFiltered = $totalData;

            $order = $param['columns'][$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            /* SORT */
            $items = SystemNotification::select($param['columnsAlias'])->where($whereCompare)
                ->offset($request->startItem)->limit($request->getItem)->orderBy($order, $dir)->get();

            $nextCount = SystemNotification::where($whereCompare)
                ->skip($request->getItem)->take($request->getItem + $request->stepItem)->get()->count();
            /* END */

            /* TO VIEW */
            $data = $items;

            $data->map(function ($item) use ($param) {

                $item->status = 'No confirmed';
                $item->statusCode = 0;
                if ($item->confirmations >= $param['minConfirmBtc']) {
                    $item->status = 'Confirmed';
                    $item->statusCode = 1;
                }
                $item->date = date('d M Y H:i', strtotime($item->date));

                unset($item->confirmations);
                return $item;
            });

        } catch (\Exception $ex) {
            return [
                'status' => false,
                'error' => $ex->getMessage(),
                'data' => [],
            ];
        }

        return [
            'status' => true,
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
            'nextCount' => $nextCount
        ];
    }

    public function withdraw(Request $request, $lang)
    {
        $user = $request->user();
        $currencyCode = config('app.currencyCode');

        $transactions = Auth::user()->transactions()->withdraws()->orderBy('id', 'Desc')->limit(10)->get();

        return view('withdraw', [
            'transactions' => $transactions,
            'currencyCode' => $currencyCode,
            'user' => $user,
            'lang' => $lang
        ]);
    }

    public function getWithdraws(Request $request)
    {
        $param = [];
        $user = $request->user();
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        try {
            if (is_null($user)) {
                throw new \Exception('user is not found');
            }

            $param['minConfirmBtc'] = $minConfirmBtc;

//            $param['columns'] = [
//                0 => 'created_at',
//                1 => 'transaction_id',
//                2 => 'status_withdraw',
//                3 => 'value'
//            ];
//
//
//            $param['columnsAlias'] = [
//                0 => 'created_at as date',
//                1 => 'transaction_id as id',
//                2 => 'status_withdraw as status',
//                3 => 'value as amount'
//            ];
//
//            $param['whereCompare'] = [
//                ['user_id', '=', $user->id]
//            ];


            $param['columns'] = [
                0 => 'created_at',
                1 => 'id',
                2 => 'withdraw_status',
                3 => 'sum'
            ];

            $param['columnsAlias'] = [
                0 => 'created_at as date',
                1 => 'id',
                2 => 'withdraw_status as status',
                3 => 'sum as amount'
            ];

            $param['whereCompare'] = [
                ['type', '=', 4],//to do fix this
                ['user_id', '=', $user->id]
            ];

            /* ACT */
            $whereCompare = $param['whereCompare'];

//            $countSum = WithdrawModel::select($param['columns'])->where($whereCompare)
//                ->skip($request->startItem)->take($request->getItem)->get()->count();

            $countSum = Transaction::select($param['columns'])->where($whereCompare)
                ->skip($request->startItem)->take($request->getItem)->get()->count();

            $totalData = $countSum;
            $totalFiltered = $totalData;

            $order = $param['columns'][$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            /* SORT */
//            $items = WithdrawModel::select($param['columnsAlias'])->where($whereCompare)
//                ->offset($request->startItem)->limit($request->getItem)->orderBy($order, $dir)->get();

            $items = Transaction::select($param['columnsAlias'])->where($whereCompare)
                ->offset($request->startItem)->limit($request->getItem)->orderBy($order, $dir)->get();

//            $nextCount = WithdrawModel::where($whereCompare)
//                ->skip($request->getItem)->take($request->getItem + $request->stepItem)->get()->count();

            $nextCount = Transaction::where($whereCompare)
                ->skip($request->getItem)->take($request->getItem + $request->stepItem)->get()->count();

            /* END */

            /* TO VIEW */
            $data = $items;

            $data->map(function ($item) use ($param) {

                $status = (int)$item->status;
                $item->statusCode = $status;
                switch ($status) {
                    case 1:
                        $item->status = 'Done';
                        break;
                    case -3:
                        $item->status = 'Waiting';
                        break;
                    default:
                        $item->status = 'Pending';
                }

                return $item;
            });

        } catch (\Exception $ex) {
            return [
                'status' => false,
                'error' => $ex->getMessage(),
                'data' => [],
            ];
        }

        return [
            'status' => true,
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
            'nextCount' => $nextCount
        ];
    }

    public function bonuses(Request $request)
    {
        $user = $request->user();
        $userId = is_null($user) ? null : $user->id;

        $bonuses = Bonus::with(['activeBonus' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('rating', 'desc')->get();

        $bonusForView = [];
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

        $currencyCode = config('app.currencyCode');

        return view('bonus', [
            'currencyCode' => $currencyCode,
            'bonusForView' => $bonusForView,
            'user' => $user
        ]);
    }

    public function settings(Request $request, $lang)
    {
        $user = $request->user();
        $currencyCode = config('app.currencyCode');

        return view('passwords', [
            'currencyCode' => $currencyCode,
            'user' => $user,
            'lang' => $lang
        ]);
    }
}
