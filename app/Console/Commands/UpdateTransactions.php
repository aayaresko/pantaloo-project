<?php

namespace App\Console\Commands;

use App\Bitcoin\Service;
use App\Transaction;
use Illuminate\Console\Command;
use League\Flysystem\Exception;

class UpdateTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:updateTransactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update number of confirmtions for each transactions';

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

        while (true) {
            $transactions = Transaction::where('confirmations', '<', 6)->where('type', 3)->get();

            foreach ($transactions as $transaction) {

                if ($data = $service->getTransaction($transaction->ext_id)) {

                    print_r($data);

                    $transaction->confirmations = $data['confirmations'];

                    $transaction->save();
                }
            }

            sleep(10);
        }
    }
}
