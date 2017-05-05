<?php

namespace FI\Listeners;

use FI\Events\TemplateDefaultChanged;
use FI\Modules\Clients\Models\Client;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Quotes\Models\Quote;

class TemplateDefaultChangedListener
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
     * @param  TemplateDefaultChanged $event
     * @return void
     */
    public function handle(TemplateDefaultChanged $event)
    {
        if ($event->template == 'invoiceTemplate')
        {
            Client::whereNull('invoice_template')->orWhere('invoice_template', $event->originalTemplate)->update(['invoice_template' => $event->newTemplate]);
            Invoice::whereNull('template')->orWhere('template', $event->originalTemplate)->update(['template' => $event->newTemplate]);
        }
        elseif ($event->template == 'quoteTemplate')
        {
            Client::whereNull('quote_template')->orWhere('quote_template', $event->originalTemplate)->update(['quote_template' => $event->newTemplate]);
            Quote::whereNull('template')->orWhere('template', $event->originalTemplate)->update(['template' => $event->newTemplate]);
        }
    }
}
