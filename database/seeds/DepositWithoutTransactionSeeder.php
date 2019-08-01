<?php

use App\Transaction;
use Illuminate\Database\Seeder;
use App\Models\SystemNotification;

class DepositWithoutTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get all transactions
        //deposits
        foreach (Transaction::where('type', 3)->orWhere('type', 13)->cursor() as $transaction) {
            $transactionId = $transaction->id;

            $checkDeposit = SystemNotification::where('transaction_id', '=', $transactionId)->first();

            //isset normal system
            if (is_null($checkDeposit)) {
                $checkDepositLike = SystemNotification::where('extra', 'like', "%:$transactionId,%")->first();
                if (is_null($checkDepositLike)) {
                    //create new
                    SystemNotification::create([
                        'user_id' => $transaction->user_id,
                        //to do config - mean deposit transactions
                        'type_id' => 1, //usual deposit
                        'value' => $transaction->sum,
                        'transaction_id' => $transaction->id,
                        'confirmations' => $transaction->confirmations,
                        'ext_id' => $transaction->ext_id,
                        'extra' => json_encode([
                            'transactionId' => $transaction->id,
                            'depositAmount' => $transaction->sum,
                        ]),
                    ]);
                } else {
                    //update value
                    if ($checkDepositLike->user_id == $transaction->user_id) {
                        SystemNotification::where('id', $checkDepositLike->id)->update([
                            'ext_id' => $transaction->ext_id,
                            'confirmations' => $transaction->confirmations,
                            'transaction_id' => $transaction->id
                        ]);
                    }
                }
            }
        }
    }
}
