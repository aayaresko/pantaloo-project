<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use Helpers\BonusHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BonusHandler extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Create a new job instance.
     *
     * BonusHandler constructor.
     * @param $user\
     */
    public function __construct($user)
    {
        $this->params['user'] = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        BonusHelper::bonusCheck($this->params['user'], 1);
    }
}
