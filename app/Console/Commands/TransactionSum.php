<?php

namespace App\Console\Commands;

use App\Models\AgentsKoef;
use App\User;
use Illuminate\Console\Command;

class TransactionSum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:sum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sum transaction';

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
        $agents = User::whereIn('role', [1, 3])->get();
        foreach ($agents as $agent) {
            $newAgent = new AgentsKoef();
            $newAgent->user_id = $agent->id;
            $newAgent->koef = 0;
            $newAgent->save();
        }
    }
}
