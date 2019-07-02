<?php

namespace App\Console\Commands;

use App\Events\UpdateEvent;
use App\Transaction;
use App\User;
use Illuminate\Console\Command;
use Pusher\Pusher;

class SendEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:send';

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
        //event(new UpdateEvent(146));
        $user = User::find(146);

        $transaction = new Transaction();
        //$transaction->ext_id = random(0,65535);
        $transaction->type = 1;
        $transaction->user()->associate($user);
        $transaction->sum = 0.1;
        dump($transaction->save());

//        $config = config('broadcasting.connections.pusher');
//
//        $pusher = new Pusher($config['key'], $config['secret'], $config['app_id'], $config['options']);
//
//        $response = $pusher->get('/channels/'.sha1(146 . config('app.key')));
//        dump($response);

    }
}
