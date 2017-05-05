<?php

namespace FI\Listeners;

use FI\Events\InvoiceRecurring;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;
use FI\Support\Parser;

class InvoiceRecurringListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MailQueueRepository $mailQueueRepository)
    {
        $this->mailQueueRepository = $mailQueueRepository;
    }

    /**
     * Handle the event.
     *
     * @param  InvoiceRecurring $event
     * @return void
     */
    public function handle(InvoiceRecurring $event)
    {
        if (config('fi.automaticEmailOnRecur') and $event->invoice->client->email)
        {
            $template = ($event->invoice->is_overdue) ? config('fi.overdueInvoiceEmailBody') : config('fi.invoiceEmailBody');

            $this->mailQueueRepository->create($event->invoice, [
                'to'         => $event->invoice->client->email,
                'cc'         => config('fi.mailDefaultCc'),
                'bcc'        => config('fi.mailDefaultBcc'),
                'subject'    => trans('fi.invoice') . ' #' . $event->invoice->number,
                'body'       => Parser::parse($event->invoice, $template),
                'attach_pdf' => config('fi.attachPdf')
            ]);
        }
    }
}
