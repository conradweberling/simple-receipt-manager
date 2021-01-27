<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;

class ClearReceiptCache
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
     * @param Cache $cache
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {

        $event_month = date("Y-m", strtotime($event->date));

        if(Cache::has($event_month)) Cache::forget($event_month);

    }
}
