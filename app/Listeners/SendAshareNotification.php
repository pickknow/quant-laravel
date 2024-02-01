<?php

namespace App\Listeners;

use App\Events\AshareEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\AshareShipped;
use Illuminate\Support\Facades\Mail;

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
        dd('got an AshareEver');
        // Mail::to('sovsov@gmail.com')->send(new AshareShipped());
        // dd('sent a email to sovsov@gmail.com');
    }
}
