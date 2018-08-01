<?php

namespace App\Console\Commands;

use App\UserBonus;
use Illuminate\Console\Command;

class BonusJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch bonus jobs';

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
        $bonuses = UserBonus::all();

        foreach ($bonuses as $bonus)
        {
            $class = $bonus->bonus->getClass();
            $bonus_obj = new $class($bonus->user);
            $bonus_obj->realActivation();
            $bonus_obj->close();
        }

        sleep(2);
    }
}
