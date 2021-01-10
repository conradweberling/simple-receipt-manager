<?php

namespace App\Listeners;

use App\Notifications\WelcomeNotification;

class SendWelcomeNotification
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
     * @param object $event
     * @return void
     */
    public function handle(object $event)
    {
        $event->user->notify(new WelcomeNotification());
    }
}
