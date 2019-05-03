<?php

namespace App\Http\Controllers\Bitcoin;

use DB;
use Log;
use Validator;
use App\Transaction;
use App\Bitcoin\Service;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Modules\Others\DebugGame;
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
        dd(2);
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
                ;
                $error = $validator->errors()->first();
                throw new \Exception($error);
            }

            //init params
            $txid = $request->txid;
            $service = new Service();

            $response = [
                'success' => true,
                'message' => ['TXID:' . $txid]
            ];

            //get transaction
            $rawTransaction = $service->getTransaction($txid);
            if (!$rawTransaction) {
                throw new \Exception('Transactions is not found in node');
            }

            $user = User::where('bitcoin_address', $rawTransaction['address'])->first();

            if (is_null($user)) {
                throw new \Exception('User with current address is not found');
            }
            $userId = $user->id;

            $transactionSystem = Transaction::where(['ext_id' => $txid])->first();

            if ($transactionSystem) {
                //update
                //check must if transaction has 1 confirmation
                //confirmations must be 1
                Transaction::where('id', $transactionSystem->id)->create([
                    'confirmations' => $rawTransaction['confirmations']
                ]);

                throw new \Exception('Transaction exists. And Updated');
            }

            $amountTransaction = $rawTransaction['amount'] * 1000;

            $transaction = Transaction::create([
                'sum' => $amountTransaction,
                'bonus_sum' => 0,
                'type' => 3,
                'user_id' => $user->id,
                'ext_id' => $rawTransaction['txid'],
                'confirmations' => $rawTransaction['confirmations']
            ]);
            array_push($response['message'], "TRANSACTION:{$transaction->id}");

            $amountTransactionFormat = GeneralHelper::formatAmount($amountTransaction);

            User::where('id', $user->id)->update([
                'balance' => DB::raw("balance+{$amountTransactionFormat}")
            ]);

            DB::commit();
        } catch (\Exception $e) {
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
        dd(2);
        $date = new \DateTime();

        $debugGame = new DebugGame();
        $debugGame->start();
        $countTransaction = 500;

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

        } catch (\Exception $e) {
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