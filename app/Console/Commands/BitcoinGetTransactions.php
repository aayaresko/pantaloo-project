<?php

namespace App\Console\Commands;

use App\User;
use App\Transaction;
use App\Bitcoin\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BitcoinGetTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:getTransactions {batchSize=100}';

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
        $service = new Service();
        $offset = 0;
        $batchSize = (int) $this->argument('batchSize');

        do {
            $raw_transactions = array_reverse($service->getTransactions($batchSize, $offset));

            foreach ($raw_transactions as $raw_transaction) {
                if ('receive' !== $raw_transaction['category']) {
                    continue;
                }

                $txid = $raw_transaction['txid'];
                $address = $raw_transaction['address'];
                $confirmations = $raw_transaction['confirmations'];
                $transaction = Transaction::where(['ext_id' => $txid])->first();

                if ($transaction instanceof Transaction) {
                    Log::info(
                        sprintf("duplicated transaction found %d. exiting...", $transaction->id)
                    );

                    return 0;
                }

                $user = User::where('bitcoin_address', $address)->first();

                if (!$user instanceof User) {
                    continue;
                }

                // do not save unconfirmed transactions
                if (config('appAdditional.minConfirmBtc') > $confirmations) {
                    continue;
                }

                $transaction = new Transaction();
                $transaction->sum = $raw_transaction['amount'] * $transaction->getMultiplier();
                $transaction->bonus_sum = 0;
                $transaction->ext_id = $txid;
                $transaction->confirmations = $confirmations;
                $transaction->address = $address;
                $transaction->type = 3;

                $transaction->user()->associate($user);

                try {
                    // TODO we need to make changes to balance only based on confirmed transactions (e.g. confirmation >= 6)
                    $user->changeBalance($transaction);
                    $transaction->save();
                } catch (\Exception $exception) {
                    Log::info(
                        sprintf("user's %s balance was not updated.", $user->email)
                    );

                    return 1;
                }
            }

            $offset += $batchSize;
        } while (count($raw_transactions) > 0);

        return 0;
    }
}
