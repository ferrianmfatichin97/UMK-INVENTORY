<?php

namespace App\Listeners;

use App\Events\JatuhTempo;
use App\Notifications\JatuhTempoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class JatuhTempoListener
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
    public function handle(JatuhTempo $event): void
    {
        $daysLeft = $event->dueDate->diffInDays(now());

        if (in_array($daysLeft, [10, 7, 1])) {
            $event->kendaraan->notify(new JatuhTempoNotification($daysLeft));
        }
    }
}
