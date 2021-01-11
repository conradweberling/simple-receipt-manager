<?php

namespace App\Providers;

use App\Events\AccountDeleted;
use App\Events\InvitationSaved;
use App\Listeners\DeleteInvitation;
use App\Listeners\SendAcceptedNotification;
use App\Listeners\SendAccountDeletedMail;
use App\Listeners\SendInvitationNotificaiton;
use App\Listeners\SendWelcomeNotification;
use App\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendWelcomeNotification::class,
            SendAcceptedNotification::class,
            DeleteInvitation::class
        ],
        AccountDeleted::class => [
            SendAccountDeletedMail::class
        ],
        InvitationSaved::class => [
            SendInvitationNotificaiton::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
