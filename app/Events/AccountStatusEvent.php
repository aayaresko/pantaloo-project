<?php

namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AccountStatusEvent extends Event
{
    use SerializesModels;

    public $user;

    public $old_status;

    public $new_status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, String $old_status, String $new_status)
    {
        $this->user = $user;
        $this->old_status = $old_status;
        $this->new_status = $new_status;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
