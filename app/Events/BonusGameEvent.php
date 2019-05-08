<?php

namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BonusGameEvent extends Event
{
    use SerializesModels;

    public $user;
    public $gameName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $game_name)
    {
        $this->user = $user;
        $this->gameName = $game_name;
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
