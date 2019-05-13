<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Providers\Intercom\Intercom;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class IntercomSendEvent extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Intercom $intercom)
    {
        Log::info('Handle job send event "' . $this->data['event_name'] . '"');

        $res = $intercom->send_event($this->data);
    }
}
