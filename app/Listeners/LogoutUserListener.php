<?php

namespace App\Listeners;

use App\Events\LogoutUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogoutUserListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogoutUserEvent  $event
     * @return void
     */
    public function handle(LogoutUserEvent $event)
    {
        $logMessage = ($event->user->isAdmin)? 
        "User Administrator " . $event->user->name. " was logout"
        :"User " . $event->user->name. " was logout";

        Log::info($logMessage);
    }
}
