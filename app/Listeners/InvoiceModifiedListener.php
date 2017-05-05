<?php

namespace FI\Listeners;

use FI\Events\InvoiceModified;
use FI\Modules\Invoices\Repositories\InvoiceCalculateRepository;

class InvoiceModifiedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(InvoiceCalculateRepository $invoiceCalculateRepository)
    {
        $this->invoiceCalculateRepository = $invoiceCalculateRepository;
    }

    /**
     * Handle the event.
     *
     * @param  InvoiceModified $event
     * @return void
     */
    public function handle(InvoiceModified $event)
    {
        $this->invoiceCalculateRepository->calculate($event->invoice->id);
    }
}
