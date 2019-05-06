<?php

namespace App\Http\Controllers\Bitcoin;

use App\Events\DepositEvent;
use DB;
use Log;
use App\User;
use Validator;
use App\Transaction;
use Helpers\BonusHelper;
use App\Bitcoin\Service;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Modules\Others\DebugGame;
use App\Models\SystemNotification;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     *
     * Get new transactions
     *
     * @param Request $request
     * @return array
     */
    public function walletNotify(Request $request)
    {
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

        DB::beginTransaction();
        try {
            //validate
            //add balidate ip
            $ipSender = GeneralHelper::visitorIpCloudFire();
            $ipExpected = config('app.bitcoinHost');
            if ($ipSender != $ipExpected) {
                throw new \Exception('Not allowed IP');
            }

            $validator = Validator::make($request->all(), [
                'txid' => 'required|string',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }

            //init params
            $txid = $request->txid;
            $service = new Service();

            //get transaction
            $rawTransaction = $service->getTransaction($txid);

            if (!$rawTransaction) {
                throw new \Exception('Transactions is not found in node');
            }

            $transactionParticipants = [];
            foreach ($rawTransaction['details'] as $detail) {

                if ($detail['category'] == 'receive') {
                    array_push($transactionParticipants, $detail['address']);
                }

            }

            $user = User::whereIn('bitcoin_address', $transactionParticipants)->first();

            if (is_null($user)) {
                throw new \Exception('User with current address is not found');
            }
            $userId = $user->id;

            $transactionSystem = Transaction::where(['ext_id' => $txid])->first();

            if (!is_null($transactionSystem)) {
                //update
                //check must if transaction has 1 confirmation
                //confirmations must be 1
                Transaction::where('id', $transactionSystem->id)->update([
                    'confirmations' => $rawTransaction['confirmations']
                ]);

                $response = [
                    'success' => true,
                    'message' => ['Transaction exists. And Updated']
                ];
            } else {
                $amountTransaction = $rawTransaction['amount'] * 1000;

                $transaction = Transaction::create([
                    'sum' => $amountTransaction,
                    'bonus_sum' => 0,
                    'type' => 3,
                    'user_id' => $user->id,
                    'ext_id' => $rawTransaction['txid'],
                    'confirmations' => $rawTransaction['confirmations']
                ]);

                $amountTransactionFormat = GeneralHelper::formatAmount($amountTransaction);

                User::where('id', $user->id)->update([
                    'balance' => DB::raw("balance+{$amountTransactionFormat}")
                ]);

                $depositNotifications = 1;
                if (!is_null($user->bonus_id)) {
                    if ((int)$user->bonus_id === 1) {
                        $depositNotifications = 2;
                    } else {
                        //check this
                        //real active if deposit got
                        $class = BonusHelper::getClass($user->bonus_id);
                        $bonusObject = new $class($user);
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
                        'depositAmount' => $amountTransaction
                    ])
                ]);

                event(new DepositEvent($user, $amountTransaction));

                $response = [
                    'success' => true,
                    'message' => ['TXID:' . $txid, "TRANSACTION:{$transaction->id}"]
                ];
            }
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

    /**
     *
     * Update transactions
     *
     * @param Request $request
     * @return array
     */
    public function blockNotify(Request $request)
    {
        $date = new \DateTime();

        $debugGame = new DebugGame();
        $debugGame->start();
        $countTransaction = 1000;

        $userId = 0;//system user

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => 11,
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date
        ]);

        try {
            //validate
            //add balidate ip
            $ipSender = GeneralHelper::visitorIpCloudFire();
            $ipExpected = config('app.bitcoinHost');
            if ($ipSender != $ipExpected) {
                throw new \Exception('Not allowed IP');
            }

            $validator = Validator::make($request->all(), [
                'blockhash' => 'required|string',
            ]);

            if ($validator->fails()) {
                ;
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }

            //init params
            $blockhash = $request->blockhash;
            $service = new Service();

            $response = [
                'success' => true,
                'message' => ['BLOCKHASH:' . $blockhash]
            ];

            //to do get block use this command and check block hash

            $minConfirmBtc = config('appAdditional.normalConfirmBtc');
            $params = [
                'badTransactions' => []
            ];

            Transaction::where('type', 3)
                ->where('confirmations', '=', $minConfirmBtc - 1)
                ->where('ext_id', '<>', '')
                ->where('ext_id', '<>', null)
                ->select(['id', 'ext_id'])
                ->chunk($countTransaction, function ($transactions) use ($service, &$params) {

                    foreach ($transactions as $transaction) {
                        try {
                            $getTransaction = $service->getTransaction($transaction->ext_id);

                            if ($getTransaction) {
                                Transaction::where('id', $transaction->id)
                                    ->update([
                                        'confirmations' => $getTransaction['confirmations']
                                    ]);
                            }
                        } catch (\Exception $ex) {
                            array_push($params['badTransactions'], $transaction->id);
                        }
                    }
                });

            $response['badTransactions'] = $params['badTransactions'];

            Transaction::where(
                [
                    ['type', '=', 3],
                    ['confirmations', '>=', 1],
                    ['confirmations', '<', $minConfirmBtc - 1]
                ]
            )->update(['confirmations' => DB::raw('confirmations + 1')]);

        } catch (\Throwable $e) {
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
