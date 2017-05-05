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
use FI\Support\Statuses\InvoiceStatuses;

class ClientCenterInvoiceController extends Controller
{
    public function __construct(ClientCenterInvoiceRepository $clientCenterInvoiceRepository, InvoiceStatuses $invoiceStatuses)
    {
        parent::__construct();
        $this->clientCenterInvoiceRepository = $clientCenterInvoiceRepository;
        $this->invoiceStatuses               = $invoiceStatuses;
        $this->clientId                      = auth()->user()->client->id;
    }

    public function index()
    {
        return view('client_center.invoices.index')
            ->with('invoices', $this->clientCenterInvoiceRepository->paginate($this->clientId))
            ->with('invoiceStatuses', $this->invoiceStatuses->statuses());
    }
}