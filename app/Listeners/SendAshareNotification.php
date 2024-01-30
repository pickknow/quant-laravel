<?php

namespace App\Listeners;

use App\Events\AshareEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAshareNotification
{
    /**
     * Create the event listener.
     */

    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(AshareEvent $event): void
    {
        dd('I got the event', $event->message);
    }
}
