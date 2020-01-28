<?php

namespace App\Console\Commands\Optimization;

use Illuminate\Console\Command;

// TODO Lior - build the logic
class ClearTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimization:clearTables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear tables';

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
        //clear type pantallo session and show linked with script integrations games
        //etc
    }
}
