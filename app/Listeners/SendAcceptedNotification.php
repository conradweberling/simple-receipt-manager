<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\AcceptedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAcceptedNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

        if($event->invitation->user_id AND $inv_user = User::find($event->invitation->user_id)) {

            $inv_user->notify(new AcceptedNotification($event->user));

        }

    }
}
