<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Bitcoin\Service;
use App\Invoice;
use App\Jobs\Withdraw;
use App\Transaction;
use App\User;
use App\Jobs\BonusHandler;
use Helpers\BonusHelper;
use App\UserBonus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Helpers\PayTrio;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class MoneyController extends Controller
{
    public function stopFreeGame()
    {
        $user = Auth::user();

        $stop = false;

        if ($user->free_spins == 0) {
            $transaction = $user->transactions()->where('type', 9)->orderBy('id', 'DESC')->first();

            if (!$transaction) throw new \Exception('Transaction not found');

            if ($user->transactions()->where('type', 10)->where('id', '>', $transaction->id)->count() > 0) {
                $stop = true;
            }
        }

        return response()->json(['stop' => $stop]);
    }

    public function balance(Request $request, $email)
    {
        //to do universal way define user to DO
        $sessionId = $_COOKIE['laravel_session'];
        $sessionLeftTime = config('session.lifetime');
        $sessionLeftTimeSecond = $sessionLeftTime * 60;
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'messages' => ['User or session is not found'],
            ]);
        }

        //to do this - fix this = use universal way
        $sessionUser = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->where('last_activity', '<=', DB::raw("last_activity + $sessionLeftTimeSecond"))
            ->first();

        if (is_null($sessionUser)) {
            return response()->json([
                'status' => false,
                'messages' => ['User or session is not found'],
            ]);
        }

        $transaction = $user->transactions()
            ->where('type', 3)->where('notification', 0)->first();

        if ($transaction) {
            $sum = $transaction->sum;
            $transaction->notification = 1;
            $transaction->save();
        } else {
            $sum = false;
        }

        //to do check active bonus
        //to do use dispatch
        dispatch(new BonusHandler($user));

        return response()->json([
            'realBalance' => $user->balance,
            'balance' => $user->getBalance(),
            'deposit' => $sum,
            'free_spins' => $user->free_spins,
	        'balance_info' => [
	        	'balance' => $user->getBalance() . ' m' . strtoupper($user->currency->title),
	        	'real_balance' => $user->getRealBalance() . ' m' . strtoupper($user->currency->title),
	        	'bonus_balance' => $user->getBonusBalance() . ' m' . strtoupper($user->currency->title),
	        ]
        ]);
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

    public function newTransactions($transaction_id)
    {
        $result = [];

        $transactions = Auth::user()->transactions()->where('type', 3)->where('id', '>', $transaction_id)->orderBy('id', 'Desc')->get();

        return response()->json($transactions->map(function ($item) {
            return [
                'date' => $item->created_at->format('d M Y H:i'),
                'id' => $item->id,
                'status' => $item->getStatus(),
                'amount' => $item->getSum()
            ];
        }));
    }

    public function allTransactions()
    {
        $result = [];

        $transactions = Auth::user()->transactions()->where('type', 3)->orderBy('id', 'Desc')->limit(10)->get();

        return response()->json($transactions->map(function ($item) {
            return [
                'date' => $item->created_at->format('d M Y H:i'),
                'id' => $item->id,
                'status' => $item->getStatus(),
                'amount' => $item->getSum()
            ];
        }));
    }

    public function deposit()
    {
        //$qr_code = 'data:image/png;base64, ' . base64_encode(QrCode::format('png')->size(100)->generate('Make me into an QrCode!'));

        $deposits = Auth::user()->transactions()->deposits()->orderBy('id', 'Desc')->limit(10)->get();

        $qrcode = new BaconQrCodeGenerator;

        return view('deposit',
            [
                'qr_code' => $qrcode->size(200)->generate(Auth::user()->bitcoin_address),
                'bitcoin_address' => Auth::user()->bitcoin_address,
                'transactions' => $deposits
            ]);

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
        BonusHelper::bonusCheck($user, 1);

        if($user->bonuses()->first()) return redirect()->back()->withErrors(['Bonus is active']);

        if($user->transactions()->deposits()->where('confirmations', '<', $minConfirmBtc)->count() > 0) return redirect()->back()->withErrors(['You have unconfirmed deposits']);

        if ($user->confirmation_required == 1 and Auth::user()->email_confirmed == 0) return redirect()->back()->withErrors(['E-mail confirmation required']);

        if ($user->transactions()->deposits()->where('confirmations', '>=', $minConfirmBtc)->count() == 0) {
            return redirect()->back()->withErrors(['You do not have any deposits.']);
        }

        $this->validate($request, [
            'address' => 'required',
            'sum' => 'required|numeric|min:1'
        ]);


        $service = new Service();

        if ($request->input('sum') < 1) return redirect()->back()->withErrors(['Minimum sum is 1']);

        if (!$service->isValidAddress($request->input('address'))) return redirect()->back()->withErrors(['Invalid bitcoin address']);

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

        return redirect()->route('withdraw', ['lang' => $lang])->with('popup', ['WITHDRAW', 'Withdraw was successfull!', 'Your withdrawal is pending approval']);
    }

    public function transfers(Request $request)
    {
        try {
            $start = Carbon::createFromFormat("Y-m-d", $request->input('start'));
        } catch (\Exception $e) {
            $start = Carbon::now();
        }

        $start->setTime(0, 0, 0);

        try {
            $end = Carbon::createFromFormat("Y-m-d", $request->input('end'));
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

            $this->dispatch(new Withdraw($transaction));

            return redirect()->route('pending')->with('msg', 'Transfer was complete!');
        } else return redirect()->back()->withErrors(['Invalid type and status']);
    }

    public function freeze(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == 0) {
            $transaction->withdraw_status = -1;
            $transaction->save();

            return redirect()->route('pending')->with('msg', 'Transaction was frozen');
        } else return redirect()->back()->withErrors(['Invalid type']);
    }

    public function unfreeze(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == -1) {
            $transaction->withdraw_status = 0;
            $transaction->save();

            return redirect()->route('pending')->with('msg', 'Transaction was unfrozen');
        } else return redirect()->back()->withErrors(['Invalid type']);
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->type == 4 and $transaction->withdraw_status == 3) {
            $transaction->withdraw_status = 0;
            $transaction->save();

            return redirect()->route('pending')->with('msg', 'Transaction was canceled');
        } else return redirect()->back()->withErrors(['Invalid type']);
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
     * USD wallet
     */

    public function depositUsd()
    {
        return view('usd.deposit');
    }

    public function depositUsdDo(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:1'
        ]);

        $invoice = new Invoice();
        $invoice->user_id = Auth::user()->id;
        $invoice->amount = $request->input('amount');
        $invoice->save();

        $form_data = [
            'currency' => 840,
            'amount' => $request->input('amount'),
            'description' => 'Пополнение счета ' . Auth::user()->email,
            'shop_invoice_id' => $invoice->id
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
            'sign' => 'required'
        ]);

        $data = [
            'invoice_id' => $request->input('shop_invoice_id'),
            'amount' => $request->input('shop_amount'),
            'sign' => $request->input('sign')
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
            throw new \Exception("Something went wrong");
        }
    }
}
