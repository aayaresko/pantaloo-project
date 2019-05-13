<?php

namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DepositEvent extends Event
{
    use SerializesModels;

    public $user;
    public $value;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $value)
    {
        $this->user = $user;
        $this->value = $value;
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
