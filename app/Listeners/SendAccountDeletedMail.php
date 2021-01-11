<?php

namespace App\Listeners;

use App\Events\AccountDeleted;
use App\Notifications\AccountDeletedNotification;
use Illuminate\Support\Facades\Notification;

class SendAccountDeletedMail
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
     * @param  AccountDeleted  $event
     * @return void
     */
    public function handle(AccountDeleted $event)
    {
        Notification::route('mail', $event->email)
            ->notify(new AccountDeletedNotification($event->name));
    }
}
