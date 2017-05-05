<?php

namespace FI\Listeners;

use FI\Events\QuoteViewed;

class QuoteViewedListener
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
     * @param  QuoteViewed $event
     * @return void
     */
    public function handle(QuoteViewed $event)
    {
        if (auth()->guest() or !auth()->user()->is_admin)
        {
            $event->quote->activities()->create(['activity' => 'public.viewed']);
            $event->quote->viewed = 1;
            $event->quote->save();
        }
    }
}
