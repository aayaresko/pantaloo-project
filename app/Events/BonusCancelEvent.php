<?php

namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BonusCancelEvent extends Event
{
    use SerializesModels;

    public $user;

    public $bonusName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $bonus_name)
    {
        $this->user = $user;
        $this->bonusName = $bonus_name;
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
