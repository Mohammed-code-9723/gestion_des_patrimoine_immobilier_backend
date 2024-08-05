<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $action;
    public $description;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $action, $description)
    {
        $this->userId = $userId;
        $this->action = $action;
        $this->description = $description;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
