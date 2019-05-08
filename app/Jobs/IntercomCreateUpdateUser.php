<?php

namespace App\Jobs;

use App\Providers\Intercom\Intercom;
use App\User;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Intercom\IntercomClient;

class IntercomCreateUpdateUser extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Intercom $intercom)
    {
        Log::info('Handle job update user "' . $this->user->email . '"');

        $intercom->create_or_update_user($this->user);

    }
}
