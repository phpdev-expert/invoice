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

use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Http\Controllers\Controller;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\InvoiceStatuses;

class InvoiceController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        parent::__construct();

        $this->invoiceRepository = $invoiceRepository;
    }

    public function index()
    {
        $status = (request('status')) ?: 'all';

        $invoices = $this->invoiceRepository->with(['client', 'activities', 'amount.invoice.currency'])
            ->paginateByStatus($status, request('search'), request('client'));

        $statuses = InvoiceStatuses::statuses();

        return view('invoices.index')
            ->with('invoices', $invoices)
            ->with('status', $status)
            ->with('statuses', $statuses)
            ->with('displaySearch', true);
    }

    public function delete($id)
    {
        $this->invoiceRepository->delete($id);

        return redirect()->route('invoices.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function pdf($id)
    {
        $invoice = $this->invoiceRepository->find($id);

        $pdf = PDFFactory::create();

        $pdf->download($invoice->html, FileNames::invoice($invoice));
    }
}