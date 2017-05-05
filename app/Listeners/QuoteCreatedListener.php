<?php

namespace FI\Listeners;

use FI\Events\QuoteCreated;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Quotes\Repositories\QuoteCalculateRepository;

class QuoteCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GroupRepository $groupRepository, QuoteCalculateRepository $quoteCalculateRepository)
    {
        $this->groupRepository          = $groupRepository;
        $this->quoteCalculateRepository = $quoteCalculateRepository;
    }

    /**
     * Handle the event.
     *
     * @param  QuoteCreated $event
     * @return void
     */
    public function handle(QuoteCreated $event)
    {
        // Create the empty quote amount record
        $this->quoteCalculateRepository->calculate($event->quote->id);

        // Increment the next id
        $this->groupRepository->incrementNextId($event->quote->group_id);
    }
}
