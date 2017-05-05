<?php

namespace FI\Listeners;

use FI\Events\PaymentCreated;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;
use FI\Support\Parser;

class PaymentCreatedListener
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
     * @param  PaymentCreated $event
     * @return void
     */
    public function handle(PaymentCreated $event)
    {
        // Do not send the payment receipt unless required conditions are met
        if (($event->checkEmailOption == true and !config('fi.automaticEmailPaymentReceipts')) or !$event->payment->invoice->client->email)
        {
            return;
        }

        $this->mailQueueRepository->create($event->payment, [
            'to'         => $event->payment->invoice->client->email,
            'cc'         => config('fi.mailDefaultCc'),
            'bcc'        => config('fi.mailDefaultBcc'),
            'subject'    => trans('fi.payment_receipt'),
            'body'       => Parser::parse($event->payment, config('fi.paymentReceiptBody')),
            'attach_pdf' => config('fi.attachPdf')
        ]);
    }
}
