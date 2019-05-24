<?php

namespace App\Http\Controllers\TestMode;

use DB;
use Log;
use Cookie;
use App\User;
use Validator;
use App\Transaction;
use Helpers\BonusHelper;
use App\Bitcoin\Service;
use Helpers\GeneralHelper;
use App\Events\DepositEvent;
use Illuminate\Http\Request;
use App\Modules\Others\DebugGame;
use App\Models\SystemNotification;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    const HASH = '$2y$10$YwrCT3o0twav46IVMAYlku57Z7tax7p0uDElBWXdfwJX3ZAEiAwSy';

    public function getTestMode(Request $request)
    {
        $testmode = !$request->cookie('testmode', false);
        return redirect('')->withCookie(cookie('testmode', $testmode));
    }

    public function sendDepositView(Request $request)
    {
        $getCookie = Cookie::get('testmode');
        if (is_null($getCookie)) {
            return redirect()->back()->withErrors(['Need include test mode']);
        }

        return view('test_mode.deposit');
    }

    public function sendDeposit(Request $request)
    {
        //double code
        $date = new \DateTime();

        $debugGame = new DebugGame();
        $debugGame->start();

        $userId = 0;//system user

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => 10,
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date
        ]);

        try {
            //to do valdiate secret key
            //validate
            //add balidate ip
            $ipSender = GeneralHelper::visitorIpCloudFlare();
            $ipExpectedArray = config('appAdditional.officeIps');
            if (!in_array($ipSender, $ipExpectedArray)) {
                throw new \Exception('Not allowed IP');
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'code' => 'required|string|min:6|max:10',
                'amount' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }

            if (!password_verify($request->code, self::HASH)) {
                throw new \Exception('Code is not correct');
            }

            //to do check code
            DB::beginTransaction();

            $user = User::where('email', $request->email)->lockForUpdate()->first();

            if (is_null($user)) {
                throw new \Exception('User with current address is not found');
            }
            $userId = $user->id;

            $amountTransaction = (float)$request->amount;

            $transaction = Transaction::create([
                'comment' => 'TEST MODE',
                'sum' => $amountTransaction,
                'bonus_sum' => 0,
                'type' => 13,
                'user_id' => $user->id,
                'ext_id' => 'TEST MODE',
                'confirmations' => 13
            ]);

            $amountTransactionFormat = GeneralHelper::formatAmount($amountTransaction);

            $depositNotifications = 1;
            if (!is_null($user->bonus_id)) {
                $class = BonusHelper::getClass($user->bonus_id);
                $bonusObject = new $class($user);
                if ((int)$user->bonus_id === 1) {
                    $depositNotifications = 2;
                    //to do check status
                    $setDeposit = $bonusObject->setDeposit($amountTransactionFormat);
//                        if ($setDeposit['success'] === false) {
//                            throw new \Exception($setDeposit['message']);
//                        }
                } else {
                    //check this
                    //real active if deposit got
                    //to do check status
                    $bonusObject->realActivation(['amount' => $amountTransactionFormat]);
                }
            }

            //to do include notifications
            SystemNotification::create([
                'user_id' => $user->id,
                //to do config - mean deposit transactions
                'type_id' => $depositNotifications,
                'value' => $amountTransaction,
                'extra' => json_encode([
                    'transactionId' => $transaction->id,
                    'depositAmount' => $amountTransaction,
                    'comment' => 'TEST MODE'
                ])
            ]);

            event(new DepositEvent($user, $amountTransaction));

            $response = [
                'success' => true,
                'msg' => 'Done.TXID:' . 'TEST MODE' ."TRANSACTION:{$transaction->id}"
            ];

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            $errorLine = $e->getLine();

            $response = [
                'success' => false,
                'msg' => $errorMessage . ' Line:' . $errorLine
            ];
        }

        $debugGameResult = $debugGame->end();

        //rewrite log
        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'user_id' => $userId,
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult)
        ]);

        return $response;
    }

}
