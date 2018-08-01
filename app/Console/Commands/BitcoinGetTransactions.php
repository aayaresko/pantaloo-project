<?php

namespace App\Console\Commands;

use App\Bitcoin\Service;
use App\Transaction;
use App\User;
use Illuminate\Console\Command;
use League\Flysystem\Exception;

class BitcoinGetTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:getTransactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect new transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = time();

        $service = new Service();

        while(true) {
            $raw_transactions = $service->getTransactions(1000);

            foreach ($raw_transactions as $raw_transaction) {
                if ($raw_transaction['category'] == 'receive') {
                    $transaction = Transaction::where(['ext_id' => $raw_transaction['txid']])->first();

                    if (!$transaction) {
                        $transaction = new Transaction();
                        $transaction->sum = $raw_transaction['amount']*1000;
                        $transaction->bonus_sum = 0;
                        $transaction->ext_id = $raw_transaction['txid'];
                        $transaction->confirmations = $raw_transaction['confirmations'];

                        $user = User::where('bitcoin_address', $raw_transaction['address'])->first();

                        if ($user) {
                            $transaction->user()->associate($user);

                            $transaction->type = 3;

                            $user->changeBalance($transaction);

                            $transaction->save();
                        }
                    } else {
                        //break;
                    }
                }
            }

            sleep(1);
        }
    }
}
