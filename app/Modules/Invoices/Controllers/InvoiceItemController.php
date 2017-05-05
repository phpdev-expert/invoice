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
use FI\Modules\Invoices\Repositories\InvoiceItemRepository;

class InvoiceItemController extends Controller
{
    public function __construct(InvoiceItemRepository $invoiceItemRepository)
    {
        parent::__construct();

        $this->invoiceItemRepository = $invoiceItemRepository;
    }

    public function delete()
    {
        $this->invoiceItemRepository->delete(request('id'));
    }
}