<?php

namespace App\Console\Commands;

use App\Transaction;
use App\Bitcoin\Service;
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
        $minConfirmBtc = config('appAdditional.normalConfirmBtc');

        while (true) {
            $transactions = Transaction::where('confirmations', '<', $minConfirmBtc)->where('type', 3)->get();

            foreach ($transactions as $transaction) {
                try {
                    $data = $service->getTransaction($transaction->ext_id);

                    if ($data) {
                        print_r($data);

                        $transaction->confirmations = $data['confirmations'];

                        $transaction->save();
                    }
                } catch (\Exception $ex) {
                    //to do logs and rollback
                    print_r($ex->getMessage());
                }
            }

            sleep(10);
        }
    }
}
