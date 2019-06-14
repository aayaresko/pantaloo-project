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

    public function account(Request $request)
    {
        return view('account');
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
        $user = $request->user();
        $deposits = [];
        $minConfirmBtc = config('appAdditional.minConfirmBtc');
        $params = ['minConfirmBtc' => $minConfirmBtc];

        try {
            if (is_null($user)) {
               throw new \Exception('user is not found');
            }

            $depositsDate = SystemNotification::select(['created_at as date', 'transaction_id as id', 'confirmations', 'value as amount'])
                ->where('user_id', $user->id)->skip($request->startItem)->take($request->getItem)->get();

            //to do new version not use transactions

            //to do script
//            if (!$depositsDate->isEmpty() and is_null($depositsDate[0]->ext_id)) {
//                $depositsDate = Transaction::select([
//                    'transactions.created_at as date',
//                    'transactions.id',
//                    'transactions.confirmations',
//                    'transactions.sum as amount'
//                ])->where('user_id', $user->id)->skip($request->startItem)->take($request->getItem)->get();
//            }

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
            'deposits' => $deposits
        ];
    }

    public function withdraw()
    {
        $transactions = Auth::user()->transactions()->withdraws()->orderBy('id', 'Desc')->limit(10)->get();

        return view('withdraw', ['transactions' => $transactions]);
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
