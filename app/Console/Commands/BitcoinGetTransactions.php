<?php

namespace App\Console\Commands;

use App\Bonuses\Bonus;
use App\User;
use App\Transaction;
use App\Bitcoin\Service;
use Helpers\BonusHelper;
use Helpers\GeneralHelper;
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
                $amount = $raw_transaction['amount'] * Transaction::getMultiplier();

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

                $this->executeBonusActivation($user, $amount);
                $this->submitBalanceTransaction($user, $amount, $txid, $confirmations, $address);
            }

            $offset += $batchSize;
        } while (count($raw_transactions) > 0);

        return 0;
    }

    private function submitBalanceTransaction(User $user, $amount, $txid, $confirmations, $address)
    {
        $transaction = new Transaction();
        $transaction->sum = $amount;
        $transaction->bonus_sum = 0;
        $transaction->ext_id = $txid;
        $transaction->confirmations = $confirmations;
        $transaction->address = $address;
        $transaction->type = 3;

        $transaction->user()->associate($user);

        try {
            $user->changeBalance($transaction);
            $transaction->save();

            return true;
        } catch (\Exception $exception) {
            Log::info(
                sprintf("user's %s balance was not updated.", $user->email)
            );

            return false;
        }
    }

    private function executeBonusActivation(User $user, $amount)
    {
        $message = "user's %s bonus %d was NOT activated.";
        $mode = 2;
        $bonusId = $user->bonus_id;
        $email = $user->email;

        if (is_null($bonusId)) {
            return false;
        }

        try {
            $class = BonusHelper::getClass($bonusId);
            $bonus = new $class($user);
        } catch (\Exception $exception) {
            Log::info(sprintf("user's %s bonus %d not found.", $email, $bonusId));

            return false;
        }

        /** @var Bonus $bonus */

        if (!$bonus->bonusAvailable(compact('mode'))) {
            Log::info(sprintf("user's %s bonus %d is NOT available.", $email, $bonusId));

            return false;
        }

        $amount = GeneralHelper::formatAmount($amount);
        $response = $bonus->realActivation(compact('amount'));
        $success = $response['success'];

        if (true === $success) {
            $message = "user's %s bonus %d was activated.";
        }

        Log::info(sprintf($message, $email, $bonusId));

        return $success;
    }
}
