<?php

namespace App\Console\Commands\Optimization;

use App\RawLog;
use Illuminate\Console\Command;

class ClearRawLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimization:ClearRawLog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear RawLog';

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
        $configOptimization = config('appAdditional.optimization');
        $configClearRawLog = $configOptimization['clearRawLog'];

        $outdated = new \DateTime();
        $outdated->modify('- ' . $configClearRawLog);
        dd($outdated);
        //RawLog::where('created_at', '<', $outdated)->delete();

    }
}
