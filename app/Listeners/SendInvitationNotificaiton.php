<?php

namespace App\Listeners;

use App\Events\InvitationSaved;
use App\Notifications\InvitationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendInvitationNotificaiton
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
     * @param  InvitationSaved  $event
     * @return void
     */
    public function handle(InvitationSaved $event)
    {

        Notification::route('mail', $event->email)
            ->notify(new InvitationNotification($event->token));

    }
}
