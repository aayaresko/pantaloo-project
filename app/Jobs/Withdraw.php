<?php

namespace App\Jobs;

use App\Bitcoin\Service;
use App\Jobs\Job;
use App\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Flysystem\Exception;

class Withdraw extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transaction = $this->transaction;

        $transaction->withdraw_status = 2;
        $transaction->save();

        $service = new Service();

        try {
            $id = $service->send($transaction->address, -1*$transaction->getBtcSum());
            $transaction->withdraw_status = 1;
            $transaction->ext_id = $id;
            $transaction->save();
        }
        catch (Exception $e)
        {
            $transaction->comment = $e->getMessage();
            $transaction->withdraw_status = -2;
            $transaction->save();
        }
    }
}
