<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Controllers;

use FI\Events\InvoiceEmailed;
use FI\Http\Controllers\Controller;
use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;
use FI\Support\Parser;
use FI\Validators\SendEmailValidator;

class InvoiceMailController extends Controller
{
    public function __construct(
        InvoiceRepository $invoiceRepository,
        MailQueueRepository $mailQueueRepository,
        SendEmailValidator $sendEmailValidator)
    {
        parent::__construct();

        $this->invoiceRepository   = $invoiceRepository;
        $this->mailQueueRepository = $mailQueueRepository;
        $this->sendEmailValidator  = $sendEmailValidator;
    }

    public function create()
    {
        $invoice = $this->invoiceRepository->find(request('invoice_id'));

        $template = ($invoice->is_overdue) ? config('fi.overdueInvoiceEmailBody') : config('fi.invoiceEmailBody');

        return view('invoices._modal_mail')
            ->with('invoiceId', $invoice->id)
            ->with('redirectTo', request('redirectTo'))
            ->with('to', $invoice->client->email)
            ->with('cc', config('fi.mailDefaultCc'))
            ->with('bcc', config('fi.mailDefaultBcc'))
            ->with('subject', trans('fi.invoice') . ' #' . $invoice->number)
            ->with('body', Parser::parse($invoice, $template));
    }

    public function store()
    {
        $validator = $this->sendEmailValidator->getValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $invoice = $this->invoiceRepository->find(request('invoice_id'));

        $this->mailQueueRepository->create($invoice, request()->except('invoice_id'));

        event(new InvoiceEmailed($invoice));
    }
}