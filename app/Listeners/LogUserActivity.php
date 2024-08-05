<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserAction;
use App\Models\Activity;

class LogUserActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserAction $event)
    {
        // Log the activity
        Activity::create([
            'user_id' => $event->userId,
            'action' => $event->action,
            'description' => $event->description,
        ]);
    }
}
