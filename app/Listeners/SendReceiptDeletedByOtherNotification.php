<?php

namespace App\Listeners;

use App\Events\ReceiptDestroyed;
use App\Models\User;
use App\Notifications\ReceiptDestroyedByOtherNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReceiptDeletedByOtherNotification
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

        $other_user = auth()->user();

        if($event->user != $other_user->id) {

            $user = User::findOrFail($event->user);
            $user->notify(new ReceiptDestroyedByOtherNotification($other_user, $event->date));

        }

    }
}
