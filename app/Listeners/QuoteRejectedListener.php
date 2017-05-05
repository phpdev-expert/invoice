<?php

namespace FI\Listeners;

use FI\Events\QuoteRejected;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;
use FI\Support\Parser;

class QuoteRejectedListener
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
     * @param  QuoteRejected $event
     * @return void
     */
    public function handle(QuoteRejected $event)
    {
        $event->quote->activities()->create(['activity' => 'public.rejected']);

        $this->mailQueueRepository->create($event->quote, [
            'to'         => $event->quote->user->email,
            'cc'         => config('fi.mailDefaultCc'),
            'bcc'        => config('fi.mailDefaultBcc'),
            'subject'    => trans('fi.quote_status_change_notification'),
            'body'       => Parser::parse($event->quote, config('fi.quoteRejectedEmailBody')),
            'attach_pdf' => config('fi.attachPdf')
        ]);
    }
}
