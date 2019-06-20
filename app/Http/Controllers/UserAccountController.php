<?php

namespace App\Http\Controllers;

use App\Bonus;
use DB;
use App\User;
use Validator;
use App\Invoice;
use App\UserBonus;
use Carbon\Carbon;
use App\Transaction;
use Helpers\PayTrio;
use App\Http\Requests;
use App\Jobs\Withdraw;
use App\Bitcoin\Service;
use Helpers\BonusHelper;
use App\ModernExtraUsers;
use App\Jobs\BonusHandler;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;
use App\Events\WithdrawalFrozenEvent;
use App\Events\WithdrawalApprovedEvent;
use App\Events\WithdrawalRequestedEvent;
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
                $infoExtraUser['lastName'] =  $request->lastName;
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

    public function getDeposits(Request $request)
    {
        $user = $request->user();
        $deposits = [];
        $minConfirmBtc = config('appAdditional.minConfirmBtc');
        $params = ['minConfirmBtc' => $minConfirmBtc];

        try {
            if (is_null($user)) {
               throw new \Exception('user is not found');
            }

            //to do new version not use transactions!!!!!!!!
            $depositsDate = SystemNotification::select(['created_at as date', 'transaction_id as id', 'confirmations', 'value as amount'])
                ->where('user_id', $user->id)->skip($request->startItem)->take($request->getItem)->get();

            $nextCount = SystemNotification::where('user_id', $user->id)
                ->skip($request->startItem + $request->getItem)->take($request->getItem)->count();

            $deposits = $depositsDate->map(function ($item, $key) use ($params) {
                $item->status = 'No confirmed';
                if ($item->confirmations >= $params['minConfirmBtc']) {
                    $item->status = 'Confirmed';
                }
                unset($item->confirmations);
                //trans
                //function for status confirmations
                return $item;
            });
        } catch (\Exception $ex) {
            return [
                'success' => false,
                'messages' => [$ex->getMessage()],
                'deposits' => $deposits

            ];
        }

        return [
            'success' => true,
            'messages' => ['Done'],
            'deposits' => $deposits,
            'countNext' => $nextCount
        ];
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

    public function withdrawDo(Request $request)
    {
        $user = Auth::user();
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        //check bonus
        if ($user->bonus_id) {
            $class = BonusHelper::getClass($user->bonus_id);
            $bonusObject = new $class($user);

            DB::beginTransaction();
            $bonusClose = $bonusObject->close(0);
            if ($bonusClose['success'] === false) {
                DB::rollBack();
            }
            DB::commit();
        }
        //check bonus

        if ($user->bonuses()->first()) {
            return redirect()->back()->withErrors(['Bonus is active']);
        }

        if ($user->transactions()->deposits()->where('confirmations', '<', $minConfirmBtc)->count() > 0) {
            return redirect()->back()->withErrors(['You have unconfirmed deposits']);
        }

        if ($user->confirmation_required == 1 and Auth::user()->email_confirmed == 0) {
            return redirect()->back()->withErrors(['E-mail confirmation required']);
        }

        //2391
        if ((int)$user->id > 2391) {
            if ($user->transactions()->deposits()->where('confirmations', '>=', $minConfirmBtc)->count() == 0) {
                return redirect()->back()->withErrors(['You do not have any deposits.']);
            }
        }

        $this->validate($request, [
            'address' => 'required',
            'sum' => 'required|numeric|min:1',
        ]);

        $service = new Service();

        if ($request->input('sum') < 1) {
            return redirect()->back()->withErrors(['Minimum sum is 1']);
        }

        if (!$service->isValidAddress($request->input('address'))) {
            return redirect()->back()->withErrors(['Invalid bitcoin address']);
        }

        $sum = $request->input('sum');
        $sum = round($sum, 5, PHP_ROUND_HALF_DOWN);
        $sum = -1 * $sum;

        $transaction = new Transaction();
        $transaction->sum = $sum;
        $transaction->bonus_sum = 0;
        $transaction->user()->associate(Auth::user());
        $transaction->type = 4;
        $transaction->withdraw_status = 0;
        $transaction->address = $request->input('address');

        try {
            Auth::user()->changeBalance($transaction);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Not enoug funds']);
        }

        //$this->dispatch(new Withdraw($transaction));

        $lang = config('currentLang');

        event(new WithdrawalRequestedEvent($user));

        return redirect()->route('withdraw', ['lang' => $lang])->with('popup', ['WITHDRAW', 'Withdraw was successfull!', 'Your withdrawal is pending approval']);
    }

    public function transfers(Request $request)
    {
        try {
            $start = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        } catch (\Exception $e) {
            $start = Carbon::now();
        }

        $start->setTime(0, 0, 0);

        try {
            $end = Carbon::createFromFormat('Y-m-d', $request->input('end'));
        } catch (\Exception $e) {
            $end = Carbon::now();
        }

        $end->setTime(23, 59, 59);

        $transfers = Transaction::whereIn('type', [3, 4]);

        $deposits = Transaction::deposits();
        $withdraws = Transaction::withdraws();

        if ($start) {
            $transfers = $transfers->where('created_at', '>=', $start);
            $deposits = $deposits->where('created_at', '>=', $start);
            $withdraws = $withdraws->where('created_at', '>=', $start);
        }

        if ($end) {
            $transfers = $transfers->where('created_at', '<=', $end);
            $deposits = $deposits->where('created_at', '<=', $end);
            $withdraws = $withdraws->where('created_at', '<=', $end);
        }

        $deposit_sum = $deposits->sum('sum');
        $withdraw_sum = $withdraws->sum('sum');
        $transfers = $transfers->orderBy('id', 'DESC')->paginate(15);

        $pending_sum = $withdraws->where('withdraw_status', 0)->sum('sum');

        return view('admin.transfers', ['transfers' => $transfers, 'deposit_sum' => $deposit_sum, 'withdraw_sum' => $withdraw_sum, 'pending_sum' => $pending_sum]);
    }

    public function stat(Request $request)
    {
    }

    public function aprove(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == 0) {
            $transaction->withdraw_status = 3;
            $transaction->save();

            $user = User::where('id', $transaction->user_id)->first();

            event(new WithdrawalApprovedEvent($user));

            $this->dispatch(new Withdraw($transaction));

            return redirect()->route('pending')->with('msg', 'Transfer was complete!');
        } else {
            return redirect()->back()->withErrors(['Invalid type and status']);
        }
    }

    public function freeze(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == 0) {
            $transaction->withdraw_status = -1;
            $transaction->save();

            $user = User::where('id', $transaction->user_id)->first();

            event(new WithdrawalFrozenEvent($user, $transaction->comment));

            return redirect()->route('pending')->with('msg', 'Transaction was frozen');
        } else {
            return redirect()->back()->withErrors(['Invalid type']);
        }
    }

    public function unfreeze(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == -1) {
            $transaction->withdraw_status = 0;
            $transaction->save();

            return redirect()->route('pending')->with('msg', 'Transaction was unfrozen');
        } else {
            return redirect()->back()->withErrors(['Invalid type']);
        }
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == 3) {
            $transaction->withdraw_status = 0;
            $transaction->save();

            return redirect()->route('pending')->with('msg', 'Transaction was canceled');
        } else {
            return redirect()->back()->withErrors(['Invalid type']);
        }
    }

    public function pending()
    {
        $frozen = Transaction::where('type', 4)->where('withdraw_status', -1)->with('user')->get();
        $pending = Transaction::where('type', 4)->where('withdraw_status', 0)->with('user')->get();
        $failed = Transaction::where('type', 4)->where('withdraw_status', -2)->with('user')->get();
        $aproved = Transaction::where('type', 4)->where('withdraw_status', 1)->with('user')->get();
        $queue = Transaction::where('type', 4)->where('withdraw_status', 3)->with('user')->get();

        return view('admin.pending', ['frozen' => $frozen, 'pending' => $pending, 'failed' => $failed, 'aproved' => $aproved, 'queue' => $queue]);
    }

    /**
     * USD wallet.
     */
    public function depositUsd()
    {
        return view('usd.deposit');
    }

    public function depositUsdDo(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
        ]);

        $invoice = new Invoice();
        $invoice->user_id = Auth::user()->id;
        $invoice->amount = $request->input('amount');
        $invoice->save();

        $form_data = [
            'currency' => 840,
            'amount' => $request->input('amount'),
            'description' => 'Пополнение счета ' . Auth::user()->email,
            'shop_invoice_id' => $invoice->id,
        ];

        $pay = new PayTrio('304221', 'x7kR0RgDg2R3JpD4duditHXsdi7ZI0Fsx');
        $data = $pay->send($form_data);

        $invoice->sign = $data['params']['sign'];
        $invoice->save();

        return redirect()->away($data['url']);
    }

    public function depositSuccess(Request $request)
    {
        return view('usd.success');
    }

    public function depositFail(Request $request)
    {
        return view('usd.fail');
    }

    public function depositCallback(Request $request)
    {
        $status = false;

        $this->validate($request, [
            'shop_invoice_id' => 'required|numeric|min:1',
            'shop_amount' => 'required|numeric|min:1',
            'sign' => 'required',
        ]);

        $data = [
            'invoice_id' => $request->input('shop_invoice_id'),
            'amount' => $request->input('shop_amount'),
            'sign' => $request->input('sign'),
        ];

        $invoice = Invoice::where(['id' => $data['invoice_id'], 'amount' => $data['amount'], 'sign' => $data['sign']])->first();

        if ($invoice && $invoice->status != 3) {
            $invoice->status = $request->input('status');
            $invoice->save();

            if ($invoice->status == 3) {
                $user = User::find($invoice->user_id);

                if ($user) {
                    $user->balance = $user->balance + $data['amount'];
                    $user->save();

                    $status = true;
                } else {
                    $invoice->status = 0;
                    $invoice->save();
                }
            } else {
                $status = true;
            }
        }

        if ($status) {
            echo 'OK';
        } else {
            throw new \Exception('Something went wrong');
        }
    }
}
