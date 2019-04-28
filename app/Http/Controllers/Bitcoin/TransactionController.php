<?php

namespace App\Http\Controllers\Bitcoin;

use DB;
use App\Bitcoin\Service;
use Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    public function walletNotify(Request $request)
    {
        dd(2);
        $service = new Service();
        $date = new \DateTime();
        $userId = 0;//system user

        $rawLogId = DB::connection('logs')->table('raw_log')->insertGetId([
            'type_id' => 1,
            'user_id' => $userId,
            'request' => GeneralHelper::fullRequest(),
            'created_at' => $date,
            'updated_at' => $date
        ]);


        //only raw transaction
        //check ip address
        //only raw transaction
        $configBitcore = config('arbex.bitcore');
        $paramsAct = [
            'transactionDone' => 2,
            'configBitcore' => $configBitcore
        ];
        $currentIp = request()->ip();
        $trulyCurrentIp = $_SERVER['SERVER_ADDR'];
        if ($currentIp === '::1' or $currentIp === $trulyCurrentIp) {
            $currentIp = 'localhost';
        }

        if ($currentIp != $configBitcore['address'] and !$request->has('txid')) {
            Log::error(['walletNotify' => 'Something is wrong by IP']);
            die();
        }
        //Log::error(['walletNotify' => 222]);
        //Log::error(['walletNotify' => $request->txid]);
        //die();
        DB::beginTransaction();
        try {
            //get transaction and checker
            $resGetTransaction = Bitcore::sendCustomRequest([
                'method' => 'gettransaction',
                'txid' => $request->txid
            ]);

            if (filter_var($resGetTransaction->success, FILTER_VALIDATE_BOOLEAN) === false) {
                Log::error(['walletNotify' => $resGetTransaction->message]);
                die();
            }

            //get raw transaction and check
            $gettransactionown = Bitcore::sendCustomRequest([
                'method' => 'gettransactionown',
                'txid' => $request->txid
            ]);

            if (filter_var($gettransactionown->success, FILTER_VALIDATE_BOOLEAN) === false) {
                Log::error(['walletNotify' => $gettransactionown->message]);
                die();
            }

            $startTime = microtime(true);
            $timeReceived = new \DateTime();
            $currentTransaction = TransactionBtcSimple::select(['id'])->where('txid', $request->txid)->first();

            if (is_null($currentTransaction)) {
                $params = [];
                $params['currencyId'] = 2; //only btc
                $params['memo'] = 'unknown';
                $params['ip'] = $currentIp;
                $params['feeAmount'] = $gettransactionown->message->fee;
                $params['amount'] = $gettransactionown->message->amount;
                //only transaction from one sender - else many sender - then - need modify
                $params['fromAddress'] = $gettransactionown->message->from[0]->address;
                $params['toAddress'] = $gettransactionown->message->to[0]->address;
                $params['balanceFrom'] = $gettransactionown->message->from[0]->balance;
                $params['balanceTo'] = $gettransactionown->message->to[0]->balance;
                $params['timeReceived'] = $timeReceived->setTimestamp(
                    $gettransactionown->message->timeReceived);
                $params['txid'] = $request->txid;

                $getDateForTransaction = SendBtcModule::getDateForTransaction($params, ['possibleNull', 'from']);
                if ($getDateForTransaction['success'] === false) {
                    Log::error(['blockNotify' => $getDateForTransaction['messageError']]);
                    die();
                }

                $params = $getDateForTransaction['params'];
                SendBtcModule::makeTransactionBtc($params);
            } else {
                //update time and first confirm
                TransactionBtcSimple::where('txid', $request->txid)->update([
                    'confirmation' => $resGetTransaction->message->confirmations,
                ]);

                if ((int)$resGetTransaction->message->confirmations >= $configBitcore['min_confirmations']) {
                    //update status transaction for this transaction
                    //to do one query - join or whereIn call back or with() where
                    $tranasctionIds = TransactionRelationship::select(['id', 'transaction_id'])
                        ->where('transactions_btc_simple_id', $currentTransaction->id)
                        ->pluck('transaction_id')->toArray();

                    $transactions = Transaction::leftJoin('wallets as w_out',
                        'w_out.id', '=', 'transactions.wallet_out')
                        ->leftJoin('wallets as w_in', 'w_in.id', '=', 'transactions.wallet_in')
                        ->whereIn('transactions.id', $tranasctionIds)
                        ->select(
                            ['transactions.id']
                        )->get();

                    foreach ($transactions as $transaction) {
                        //update wallet and status in transaction
                        Transaction::where('id', $transaction->id)->update([
                            'status' => $paramsAct['transactionDone']
                        ]);
                    }

                }
            }
            $endTime = round(microtime(true) - $startTime, 4);
            Log::info(['walletNotify' => [
                'runTime' => $endTime,
                'memory' => memory_get_peak_usage(),
            ]]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(['walletNotify' => $e->getMessage()]);
            die();
        }
        DB::commit();

        DB::connection('logs')->table('raw_log')->where('id', $rawLogId)->update([
            'response' => json_encode($response),
            'extra' => json_encode($debugGameResult)
        ]);

        return response()->json([
            'success' => true
        ]);

    }

    public function blockNotify(Request $request)
    {
        //only raw transaction
        //check ip address
        //only raw transaction
        $howMany = 500;
        $configBitcore = config('arbex.bitcore');
        $paramsAct = [
            'transactionDone' => 2,
            'configBitcore' => config('arbex.bitcore'),
        ];

        $responseTxids = [
            'confirm' => 0,
            'notConfirmed' => 0,
            'erroneous' => 0,
        ];
        $currentIp = request()->ip();
        $trulyCurrentIp = $_SERVER['SERVER_ADDR'];
        if ($currentIp === '::1' or $currentIp === $trulyCurrentIp) {
            $currentIp = 'localhost';
        }

        if ($currentIp != $configBitcore['address'] and !$request->has('blockhash')) {
            Log::error(['blockNotify' => 'Something is wrong by IP']);
            die();
        }

        DB::beginTransaction();
        try {
            $startTime = microtime(true);
            //TO DO OPTIMISATION - COUNT NEW BLOCK AND UPDATE STATUS - ANYWHERE +1, WHEN
            //CONFIRMATION = 5 - SEND TO NODE BTC THIS ITEM - CHECK (UPDATE TO 6 OR SET HIS REAL VALUE)
            //OR GET TRANSACTION IN BLOCK IN OWN  $listsinceblock?
            //get all where confirmation < {value}
            TransactionBtcSimple::where('confirmation', '=', $configBitcore['min_confirmations'] - 1)
                ->select(['id', 'txid'])->chunk($howMany, function ($tranasctionBtcItems)
                use ($paramsAct, &$responseTxids) {
                    $tranasctionBtc = $tranasctionBtcItems->toArray();

                    //create array -> send array -> return array with confirmations
                    $paramsCheckConfirm = [
                        'method' => 'checkconfirmrawtransaction',
                        'txids' => json_encode($tranasctionBtc),
                        'minConfirm' => $paramsAct['configBitcore']['min_confirmations']
                    ];

                    //Add to index column for this table
                    $checkConfirm = Bitcore::sendCustomRequest($paramsCheckConfirm);
                    if (filter_var($checkConfirm->success, FILTER_VALIDATE_BOOLEAN) === false) {
                        Log::error(['blockNotify' => $checkConfirm->message]);
                        die();
                    }
                    $txids = $checkConfirm->message->txids;
                    $confirm = $txids->confirm;
                    $notConfirmed = $txids->notConfirmed;
                    $erroneous = $txids->erroneous;

                    //Erroneous
                    $erroneousUpdate = [];
                    foreach ($erroneous as $erroneousItem) {
                        array_push($erroneousUpdate, $erroneousItem->id);
                    }

                    TransactionBtcSimple::whereIn('id', $erroneousUpdate)->update([
                        'confirmation' => -1,
                    ]);

                    //Not confirmed
                    foreach ($notConfirmed as $notConfirmedItem) {
                        TransactionBtcSimple::where('id', $notConfirmedItem->id)->update([
                            'confirmation' => $notConfirmedItem->confirmation,
                        ]);
                    }
                    //Confirmed
                    $tranasctionHashIds = [];
                    foreach ($confirm as $confirmItem) {
                        array_push($tranasctionHashIds, $confirmItem->id);
                    }
                    //to do one query - join or whereIn call back or with() where
                    $tranasctionIds = TransactionRelationship::select(['id', 'transaction_id'])
                        ->whereIn('transactions_btc_simple_id', $tranasctionHashIds)
                        ->pluck('transaction_id')->toArray();

                    $transactions = Transaction::leftJoin('wallets as w_out',
                        'w_out.id', '=', 'transactions.wallet_out')
                        ->leftJoin('wallets as w_in', 'w_in.id', '=', 'transactions.wallet_in')
                        ->whereIn('transactions.id', $tranasctionIds)
                        ->select(
                            ['transactions.id']
                        )->get();

                    TransactionBtcSimple::whereIn('id', $tranasctionHashIds)->update([
                        'confirmation' => $paramsAct['configBitcore']['min_confirmations'],
                    ]);

                    foreach ($transactions as $transaction) {
                        //update wallet and status in transaction
                        Transaction::where('id', $transaction->id)->update([
                            'status' => $paramsAct['transactionDone']//status done
                        ]);
                    }

                    $responseTxids['confirm'] += count($confirm);
                    $responseTxids['notConfirmed'] += count($notConfirmed);
                    $responseTxids['erroneous'] += count($erroneous);
                });

            TransactionBtcSimple::where(
                [
                    ['confirmation', '>=', 1],
                    ['confirmation', '<', $paramsAct['configBitcore']['min_confirmations'] - 1]
                ]
            )->update(['confirmation' => DB::raw('confirmation+1')]);

            $endTime = round(microtime(true) - $startTime, 4);
            Log::info(['blockNotify' => [
                'runTime' => $endTime,
                'memory' => memory_get_peak_usage(),
                'txids' => $responseTxids
            ]]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(['blockNotify' => $e->getMessage()]);
            die();
        }
        DB::commit();
    }

}
