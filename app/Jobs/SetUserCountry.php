<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use Helpers\GeneralHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetUserCountry extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    protected $country;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->country = GeneralHelper::visitorCountryCloudFlare();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->country = $this->country;
        $this->user->save();
    }
}
