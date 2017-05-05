<?php

namespace FI\Listeners;

use FI\Events\InvoiceCreated;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Invoices\Repositories\InvoiceCalculateRepository;

class InvoiceCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GroupRepository $groupRepository, InvoiceCalculateRepository $invoiceCalculateRepository)
    {
        $this->groupRepository            = $groupRepository;
        $this->invoiceCalculateRepository = $invoiceCalculateRepository;
    }

    /**
     * Handle the event.
     *
     * @param  InvoiceCreated $event
     * @return void
     */
    public function handle(InvoiceCreated $event)
    {
        // Create the empty invoice amount record
        $this->invoiceCalculateRepository->calculate($event->invoice->id);

        // Increment the next id
        $this->groupRepository->incrementNextId($event->invoice->group_id);
    }
}
