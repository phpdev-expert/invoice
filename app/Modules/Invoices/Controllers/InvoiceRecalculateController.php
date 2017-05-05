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

use FI\Modules\Invoices\Repositories\InvoiceCalculateRepository;
use FI\Http\Controllers\Controller;

class InvoiceRecalculateController extends Controller
{
    public function __construct(InvoiceCalculateRepository $invoiceCalculateRepository)
    {
        parent::__construct();
        $this->invoiceCalculateRepository = $invoiceCalculateRepository;
    }

    public function recalculate()
    {
        try
        {
            $this->invoiceCalculateRepository->calculateAll();
        }
        catch (\Exception $e)
        {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true, 'message' => trans('fi.recalculation_complete')], 200);
    }
}