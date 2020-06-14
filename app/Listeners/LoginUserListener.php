<?php

namespace App\Listeners;

use App\Events\LoginUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LoginUserListener
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
     * @param  LoginUserEvent  $event
     * @return void
     */
    public function handle(LoginUserEvent $event)
    {
        $logMessage = ($event->user->isAdmin)? 
        "User Administrator " . $event->user->name. " was authenticated"
        :"User " . $event->user->name. " was authenticated";

        Log::info($logMessage);
    }
}
