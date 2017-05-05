<?php

namespace FI\Listeners;

use FI\Events\InvoiceViewed;

class InvoiceViewedListener
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
     * @param  InvoiceViewed $event
     * @return void
     */
    public function handle(InvoiceViewed $event)
    {
        if (auth()->guest() or !auth()->user()->is_admin)
        {
            $event->invoice->activities()->create(['activity' => 'public.viewed']);
            $event->invoice->viewed = 1;
            $event->invoice->save();
        }
    }
}
