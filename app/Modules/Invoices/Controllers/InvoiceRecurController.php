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

use FI\Http\Controllers\Controller;
use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\Invoices\Repositories\RecurringInvoiceRepository;
use FI\Modules\Invoices\Validators\RecurringInvoiceValidator;
use FI\Support\Frequency;

class InvoiceRecurController extends Controller
{
    public function __construct(
        InvoiceRepository $invoiceRepository,
        RecurringInvoiceRepository $recurringInvoiceRepository,
        RecurringInvoiceValidator $recurringInvoiceValidator
    )
    {
        parent::__construct();

        $this->invoiceRepository          = $invoiceRepository;
        $this->recurringInvoiceRepository = $recurringInvoiceRepository;
        $this->recurringInvoiceValidator  = $recurringInvoiceValidator;
    }

    public function create()
    {
        $invoice = $this->invoiceRepository->find(request('invoice_id'));

        return view('invoices._modal_recur')
            ->with('invoice', $invoice)
            ->with('frequencies', Frequency::lists());
    }

    public function store()
    {
        $validator = $this->recurringInvoiceValidator->getValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $this->recurringInvoiceRepository->create(request()->all(), request('invoice_id'));

        session()->flash('alertSuccess', trans('fi.recurring_invoice_created'));

        return response()->json(['success' => true], 200);
    }
}