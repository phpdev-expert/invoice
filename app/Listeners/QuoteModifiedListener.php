<?php

namespace FI\Listeners;

use FI\Events\QuoteModified;
use FI\Modules\Quotes\Repositories\QuoteCalculateRepository;

class QuoteModifiedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(QuoteCalculateRepository $quoteCalculateRepository)
    {
        $this->quoteCalculateRepository = $quoteCalculateRepository;
    }

    /**
     * Handle the event.
     *
     * @param  QuoteModified $event
     * @return void
     */
    public function handle(QuoteModified $event)
    {
        // Calculate the quote and item amounts
        $this->quoteCalculateRepository->calculate($event->quote->id);
    }
}
