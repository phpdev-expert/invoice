<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\ClientCenter\Repositories\ClientCenterInvoiceRepository;
use FI\Modules\ClientCenter\Repositories\ClientCenterPaymentRepository;
use FI\Modules\ClientCenter\Repositories\ClientCenterQuoteRepository;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Support\Statuses\QuoteStatuses;

class ClientCenterDashboardController extends Controller
{
    public function __construct(
        ClientCenterInvoiceRepository $clientCenterInvoiceRepository,
        ClientCenterQuoteRepository $clientCenterQuoteRepository,
        ClientCenterPaymentRepository $clientCenterPaymentRepository,
        InvoiceStatuses $invoiceStatuses,
        QuoteStatuses $quoteStatuses)
    {
        parent::__construct();
        $this->clientCenterInvoiceRepository = $clientCenterInvoiceRepository;
        $this->clientCenterQuoteRepository   = $clientCenterQuoteRepository;
        $this->clientCenterPaymentRepository = $clientCenterPaymentRepository;
        $this->invoiceStatuses               = $invoiceStatuses;
        $this->quoteStatuses                 = $quoteStatuses;
        $this->clientId                      = auth()->user()->client->id;
    }

    public function index()
    {
        return view('client_center.index')
            ->with('invoices', $this->clientCenterInvoiceRepository->getRecent($this->clientId))
            ->with('quotes', $this->clientCenterQuoteRepository->getRecent($this->clientId))
            ->with('payments', $this->clientCenterPaymentRepository->getRecent($this->clientId))
            ->with('invoiceStatuses', $this->invoiceStatuses->statuses())
            ->with('quoteStatuses', $this->quoteStatuses->statuses());
    }
}