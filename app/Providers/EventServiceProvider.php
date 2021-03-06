<?php

namespace App\Providers;

use App\Events\AccountDeleted;
use App\Events\InvitationSaved;
use App\Events\ReceiptCreated;
use App\Events\ReceiptDeleted;
use App\Events\ReceiptDestroyed;
use App\Listeners\ClearGlobalCache;
use App\Listeners\DeleteInvitation;
use App\Listeners\ClearReceiptCache;
use App\Listeners\SendAcceptedNotification;
use App\Listeners\SendAccountDeletedMail;
use App\Listeners\SendInvitationNotificaiton;
use App\Listeners\SendReceiptDeletedByOtherNotification;
use App\Listeners\SendReceiptDestroyedByOtherNotification;
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
            DeleteInvitation::class,
            ClearGlobalCache::class
        ],
        AccountDeleted::class => [
            SendAccountDeletedMail::class,
            ClearGlobalCache::class
        ],
        InvitationSaved::class => [
            SendInvitationNotificaiton::class
        ],
        ReceiptDeleted::class => [
            SendReceiptDeletedByOtherNotification::class,
            ClearReceiptCache::class
        ],
        ReceiptCreated::class => [
            ClearReceiptCache::class
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
