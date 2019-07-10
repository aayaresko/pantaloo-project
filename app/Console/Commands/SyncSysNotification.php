<?php

namespace App\Console\Commands;

use App\Models\SystemNotification;
use App\Transaction;
use Illuminate\Console\Command;

class SyncSysNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sysn:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $sysn = SystemNotification::all();
        foreach ($sysn as $n) {
            $transactionId = json_decode($n->extra)->transactionId;
            $transaction = Transaction::find($transactionId);
            if ($transaction) {
                echo $transaction->id . ' ' . $transaction->confirmations . ' ' . $transaction->ext_id . "\n";
                $n->transaction_id = $transaction->id;
                $n->confirmations = $transaction->confirmations;
                $n->ext_id = $transaction->ext_id;
                $n->save();
            } else {
                dd('WTF!!!');
            }
        }
    }
}
