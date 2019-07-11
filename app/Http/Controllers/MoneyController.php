<?php

namespace App\Http\Controllers;

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
use Helpers\GeneralHelper;
use App\Mail\BaseMailable;
use App\Jobs\BonusHandler;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\WithdrawalFrozenEvent;
use App\Events\WithdrawalApprovedEvent;
use App\Events\WithdrawalRequestedEvent;
use App\Models\Withdraw as WithdrawModel;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class MoneyController extends Controller
{
    public function stopFreeGame()
    {
        $user = Auth::user();

        $stop = false;

        if ($user->free_spins == 0) {
            $transaction = $user->transactions()->where('type', 9)->orderBy('id', 'DESC')->first();

            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            if ($user->transactions()->where('type', 10)->where('id', '>', $transaction->id)->count() > 0) {
                $stop = true;
            }
        }

        return response()->json(['stop' => $stop]);
    }

    public function balance(Request $request, $email)
    {
        $request->session()->reflash();

        try {
            //to do universal way define user to DO
            //$sessionId = $_COOKIE['casinobit_session'];
            $sessionLeftTime = config('session.lifetime');
            $sessionLeftTimeSecond = $sessionLeftTime * 60;

            $date = new \DateTime();
            $minimumAllowedActivity = $date->modify("-$sessionLeftTimeSecond second");

            //to do this - fix this = use universal way for get sessino user
            //select nesessary fields
//            $user = User::select(['users.*', 's.id as session_id'])
//                ->join('sessions as s', 's.user_id', '=', 'users.id')
//                ->where('users.email', $email)
//                ->where('s.id', $sessionId)
//                ->where('s.last_activity', '>=', $minimumAllowedActivity)
//                ->first();

//            if (is_null($user) or is_null($user->session_id)) {
//                return response()->json([
//                    'status' => false,
//                    'messages' => ['User or session is not found'],
//                ]);
//            }

//        $sessionUser = DB::table('sessions')
//            ->where('id', $sessionId)
//            ->where('user_id', $user->id)
//            ->where('last_activity', '<=', DB::raw("last_activity + $sessionLeftTimeSecond"))
//            ->first();

            /*if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'messages' => ['User or session is not found'],
                ]);
            }*/

            $user = Auth::check() ? Auth::user() : null;

            if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'messages' => ['User or session is not found'],
                ]);
            }

            //to do once in 10 seconds and use other table for notifications

            //to do fix this
            $notificationTransactionDeposit = SystemNotification::where('user_id', $user->id)
                ->where(function ($query) {
                    $query->where('type_id', '=', 1)
                        ->orWhere('type_id', '=', 2);
                })
                ->where('status', 0)
                ->first();

            if ($notificationTransactionDeposit) {
                SystemNotification::where('id', $notificationTransactionDeposit->id)->update([
                    'status' => 1,
                ]);

                $extraSystemNotification = json_decode($notificationTransactionDeposit->extra);
                $sum = $extraSystemNotification->depositAmount;
            } else {
                $sum = false;
            }

//        $transaction = $user->transactions()
//            ->where('type', 3)->where('notification', 0)->first();
//
//        if ($transaction) {
//            $sum = $transaction->sum;
//            $transaction->notification = 1;
//            $transaction->save();
//        } else {
//            $sum = false;
//        }

            //to do check active bonus
            //to do not use dispatch
            if ($user->bonus_id) {
                $checkFrequencyBonus = config('bonus.checkFrequency');
                if (rand(1, $checkFrequencyBonus) === 1) {
                    DB::beginTransaction();
                    //get user for lock
                    $currentUser = User::where('id', $user->id)
                        ->lockForUpdate()->first();

                    $class = BonusHelper::getClass($currentUser->bonus_id);
                    $bonusObject = new $class($currentUser);

                    $bonusClose = $bonusObject->close(1);
                    if ($bonusClose['success'] === false) {
                        DB::rollBack();
                    }
                    DB::commit();
                }
            }
            //$sum = 10;
            $response = [
                'success' => true,
                'realBalance' => $user->balance,
                'balance' => $user->getBalance(),
                'deposit' => $sum,
                'depositId' => isset($extraSystemNotification) && isset($extraSystemNotification->transactionId) ? $extraSystemNotification->transactionId : false,
                'depositComment' => isset($extraSystemNotification) && isset($extraSystemNotification->comment) ? $extraSystemNotification->comment : false,
                'free_spins' => $user->free_spins,
                'balance_info' => [
                    'balance' => $user->getBalance() . ' ' . config('app.currencyCode'),
                    'real_balance' => $user->getRealBalance() . ' ' . config('app.currencyCode'),
                    'bonus_balance' => $user->getBonusBalance() . ' ' . config('app.currencyCode'),
                ],
            ];
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function userActive(Request $request)
    {
        return response()->json([
            'status' => true,
        ]);
    }

    public function bitcoin()
    {
        $service = new Service();

        $data = $service->getWalletInfo();

        return view('admin.bitcoin', ['balance' => $data['balance']]);
    }

    public function sendBitcoins(Request $request)
    {
        $service = new Service();

        try {
            $data = $service->send($request->input('bitcoin_address'), $request->input('sum'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('msg', 'Bitcoins was send!<br><br>Transaction id: ' . $data);
    }

    public function newTransactions(Request $request, $transaction_id)
    {
        //temporary
        $lang = $request->cookie('langs');
        $languages = \Helpers\GeneralHelper::getListLanguage();
        if (in_array($lang, $languages)) {
            app()->setLocale($lang);
        }
        //temporary

        $result = [];

        $transactions = Auth::user()->transactions()->where('type', 3)->where('id', '>', $transaction_id)->orderBy('id', 'Desc')->get();

        return response()->json($transactions->map(function ($item) {
            return [
                'date' => $item->created_at->format(trans('date.action_deposit')),
                'id' => $item->id,
                'status' => $item->getStatus(),
                'amount' => $item->getSum(),
            ];
        }));
    }

    public function allTransactions(Request $request)
    {
        //temporary
        $lang = $request->cookie('langs');
        $languages = \Helpers\GeneralHelper::getListLanguage();
        if (in_array($lang, $languages)) {
            app()->setLocale($lang);
        }
        //temporary

        $result = [];

        $transactions = Auth::user()->transactions()->where('type', 3)->orderBy('id', 'Desc')->limit(10)->get();

        return response()->json($transactions->map(function ($item) {
            return [
                'date' => $item->created_at->format(trans('date.action_deposit')),
                'id' => $item->id,
                'status' => $item->getStatus(),
                'amount' => $item->getSum(),
            ];
        }));
    }

    /**
     * to do delete
     *
     * @deprecated
     */
    public function deposit()
    {
        //$qr_code = 'data:image/png;base64, ' . base64_encode(QrCode::format('png')->size(100)->generate('Make me into an QrCode!'));

        $deposits = Auth::user()->transactions()->deposits()->orderBy('id', 'Desc')->limit(10)->get();

        $qrcode = new BaconQrCodeGenerator;

        return view('deposit',
            [
                'qr_code' => $qrcode->size(200)->generate(Auth::user()->bitcoin_address),
                'bitcoin_address' => Auth::user()->bitcoin_address,
                'transactions' => $deposits,
            ]);
    }

    public function withdraw()
    {
        $transactions = Auth::user()->transactions()->withdraws()->orderBy('id', 'Desc')->limit(10)->get();

        return view('withdraw', ['transactions' => $transactions]);
    }

    public function withdrawDo(Request $request)
    {

        //preparations
        $errors = [];
        $date = new \DateTime();
        $user = $request->user();
        $userId = $user->id;
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => config('appAdditional.rawLogKey.withdraw'),
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

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
        //preparations

        //action
        try {
            //check bonus
            if ($user->bonuses()->first()) {
                $errors = ['You can not make a withdrawal. Bonus is active'];
                throw new \Exception('bonus_is_active');
            }

            $countNoConfirmDeposits = SystemNotification::where('user_id', $user->id)
                ->where('confirmations', '<', $minConfirmBtc)->count();
            //if ($user->transactions()->deposits()->where('confirmations', '<', $minConfirmBtc)->count() > 0) {

            if ($countNoConfirmDeposits > 0) {
                $errors = ['You have unconfirmed deposits'];
                throw new \Exception('unconfirmed_deposits');
            }

            if ($user->confirmation_required == 1 and $user->email_confirmed == 0) {
                $errors = ['E-mail confirmation required'];
                throw new \Exception('confirmation_required');
            }

            //sometimes fix this - after first start
            //TO DO THIS - if use deposit
            $countConfirmDeposits = SystemNotification::where('user_id', $user->id)
                ->where('confirmations', '>=', $minConfirmBtc)->count();

            if (!GeneralHelper::isTestMode()) {
                if ((int)$user->id > 2391) {
                    //if ($user->transactions()->deposits()->where('confirmations', '>=', $minConfirmBtc)->count() == 0) {
                    if ($countConfirmDeposits == 0) {
                        $errors = ['You do not have any deposits.'];
                        throw new \Exception('do_not_have_any_deposits.');
                    }
                }
            }

            //main act
            $validator = Validator::make($request->all(), [
                'address' => 'required|string',
                'sum' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                $validatorErrors = $validator->errors()->toArray();
                array_walk_recursive($validatorErrors, function ($item) use (&$errors) {
                    array_push($errors, $item);
                });
                throw new \Exception('validation');
            }

            //to do fix this double checker
            if ($request->input('sum') < 1) {
                $errors = ['Minimum sum is 1'];
                throw new \Exception('minimum_sum_is');
            }

            if (!GeneralHelper::isTestMode()) {
                $service = new Service();
                if (!$service->isValidAddress($request->input('address'))) {
                    $errors = ['Invalid bitcoin address'];
                    throw new \Exception('invalid_address');
                }
            }

            $sum = $request->input('sum');
            $sum = GeneralHelper::formatAmount($sum);
            $sum = -1 * $sum;

            DB::beginTransaction();

            $actualUser = User::select(['id', 'balance'])
                ->where('id', $user->id)->lockForUpdate()->first();

            if ((float)$actualUser->balance < abs($sum)) {
                $errors = ['Not enough funds'];
                throw new \Exception('not_enough_funds');
            }

            $withdrawStatus = -3;

            $transaction = Transaction::create([
                'sum' => $sum,
                'bonus_sum' => 0,
                'user_id' => $user->id,
                'type' => 4,
                'withdraw_status' => $withdrawStatus,
                'address' => $request->input('address'),
                'comment' => 'withdraw',
            ]);

            $transaction->address = $request->input('address');
            $transaction->save();

            $withdraw = WithdrawModel::create([
                'user_id' => $user->id,
                'value' => $sum,
                'status_withdraw' => $withdrawStatus,
                'to_address' => $request->input('address'),
                'transaction_id' => $transaction->id
            ]);

            //edit balance user
            User::where('id', $user->id)
                ->update(['balance' => DB::raw("balance+{$sum}")]);

            //this code we can deleting
            $userAfterUpdate = User::select('id', 'balance')->where('id', $user->id)->first();

            if ($userAfterUpdate->balance < 0) {
                $errors = ['Not enough funds'];
                throw new \Exception('not_enough_funds');
            }
            //this code we can deleting

            DB::commit();

            DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
                'response' => json_encode([
                    'transaction_id' => $transaction->id,
                    'withdraw_id' => $withdraw->id,
                    'status_withdraw' => $withdrawStatus,
                ])
            ]);

            $lang = config('currentLang');

            event(new WithdrawalRequestedEvent($user));

            //not main code task set this code************
            try {
                curl_setopt_array($ch = curl_init(), array(
                    CURLOPT_URL => "https://api.pushover.net/1/messages.json",
                    CURLOPT_POSTFIELDS => array(
                        "token" => "uf33kvmacm6p4cn7sxc87r9nrc799t",
                        "user" => "overpush@protonmail.com",
                        "message" => "hello world",
                    ),
                    CURLOPT_SAFE_UPLOAD => true,
                    CURLOPT_RETURNTRANSFER => true,
                ));
                curl_exec($ch);
                curl_close($ch);
            } catch (\Exception $ex) {
                //nothing
            }
            //not main code task set this code*********


            //SEND EMAIL CONFIRM *************
            //TO DO THIS IN EVENT
            //to do check withdraw only************future
            //$link = md5($withdraw->id . config('app.key') . $withdraw->user_id);

            $link = md5($transaction->id . config('app.key') . $transaction->user_id);

            $mail = new BaseMailable('emails.confirm_withdraw',
                [
                    'link' => sprintf('%s/%s?link=%s&email=%s', url('/'), 'withdrawActivation', $link, $user->email),
                    'valueWithCurrency' => abs($sum) . ' ' . config('app.currencyCode')
                ]);

            $mail->subject('Confirm withdrawal');
            Mail::to($user)->send($mail);
            //SEND EMAIL CONFIRM *************
        } catch (\Exception $ex) {
            DB::rollBack();

            if (empty($errors)) {
                $errors = ['Some is wrong'];
            }

            DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
                'response' => json_encode([
                    'errors' => [$ex->getMessage()]
                ])
            ]);

            return redirect()->back()->withErrors($errors);
        }

        return redirect()->route('withdraw', ['lang' => $lang])
            ->with('popup', ['Withdrawal!', 'A confirmation email has been sent to the mail.']);
    }

    public function withdrawActivation(Request $request)
    {
        $errors = [];
        $date = new \DateTime();

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => config('appAdditional.rawLogKey.withdraw'),
            'user_id' => 0,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        try {
            //main act
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'link' => 'required|string',
            ]);

            if ($validator->fails()) {
                $validatorErrors = $validator->errors()->toArray();
                array_walk_recursive($validatorErrors, function ($item) use (&$errors) {
                    array_push($errors, $item);
                });
                throw new \Exception('validation');
            }

            $user = User::where('email', $request->email)->first();

            if (is_null($user)) {
                $errors = ['Problem.User is not found'];
                throw new \Exception('no_user');
            }
            $userId = $user->id;

            //make check
            $keyApp = config('app.key');

            //to do check withdraw only************future
            //to do check withdraw only************future
            //to do use this table
//            $checkWithdraw = WithdrawModel::where('user_id', $user->id)
//                ->where(DB::raw("MD5(concat(id, '$keyApp', $userId))"), $request->link)->first();

            $checkTransaction = Transaction::where('user_id', $user->id)
                ->where('type', 4)
                ->where(DB::raw("MD5(concat(id, '$keyApp', $userId))"), $request->link)->first();

            //to do use $checkWithdraw
            if (is_null($checkTransaction)) {
                $errors = ['Some is wrong. Hash'];
                throw new \Exception('problem_hash');
            }

            //edit status transactions and withdraw
            DB::beginTransaction();

            $withdrawStatus = 0;

            Transaction::where('id', $checkTransaction->id)->update([
                'withdraw_status' => $withdrawStatus,
            ]);

            WithdrawModel::where('transaction_id', $checkTransaction->id)->update([
                'status_withdraw' => $withdrawStatus,
            ]);

//            WithdrawModel::where('id', $checkWithdraw->id)->update([
//                'status_withdraw' => 0,
//            ]);

//            Transaction::where('id', $checkWithdraw->transaction_id)->update([
//                'withdraw_status' => 0,
//            ]);

            DB::commit();

            DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
                'response' => json_encode([
                    'transaction_id' => $checkTransaction->id,
                    //'withdraw_id' => $checkWithdraw->id,
                    'status_withdraw' => $withdrawStatus,
                ])
            ]);

        } catch (\Exception $ex) {
            DB::rollBack();
            if (empty($errors)) {
                $errors = ['Some is wrong'];
            }

            DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
                'response' => json_encode([
                    'errors' => [$ex->getMessage()]
                ])
            ]);

            //to do transaction
            return redirect()->route('withdraw', ['lang' => app()->getLocale()])->withErrors($errors);
        }

        return redirect()->route('withdraw', ['lang' => app()->getLocale()])
            ->with('popup', ['Withdrawal!', 'Withdrawal has been confirmed!']);
    }

    public function approve(Transaction $transaction)
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

        return view('admin.pending', [
            'frozen' => $frozen,
            'pending' => $pending,
            'failed' => $failed,
            'aproved' => $aproved,
            'queue' => $queue
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * to fix this method
     * methow below contains getting deposits by transaction we use model system notification for deposits etc
     * need use nessesary model
     */
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

        return view('admin.transfers', [
            'transfers' => $transfers,
            'deposit_sum' => $deposit_sum,
            'withdraw_sum' => $withdraw_sum,
            'pending_sum' => $pending_sum
        ]);
    }

    public function stat(Request $request)
    {
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
