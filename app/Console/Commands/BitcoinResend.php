<?php

namespace App\Console\Commands;

use App\Transaction;
use App\Bitcoin\Service;
use Illuminate\Console\Command;

class BitcoinResend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:resend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend accepted bitcoins to secure address';

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
        $minConfirmBtc = config('appAdditional.minConfirmBtc');
        $service = new Service();

        $transactions = Transaction::where('resend_status', 1)->where('confirmations', '>=', $minConfirmBtc)->get();

        foreach ($transactions as $transaction) {
            $transaction->resend_status = 1;
            $transaction->save();

            $user = $transaction->user;

            $data = $service->send($user->bitcoin_address, '1HcUE8zWbeC7PFQnkDQh5D1aiTkVBRnGi8', $transaction->sum);

            print_r($data);
            exit;
        }
    }
}
