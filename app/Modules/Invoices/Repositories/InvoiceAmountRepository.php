<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Repositories;

use DB;
use FI\Modules\Invoices\Models\InvoiceAmount;

class InvoiceAmountRepository
{
    public function findByInvoiceId($invoiceId)
    {
        return InvoiceAmount::where('invoice_id', '=', $invoiceId)->first();
    }

    public function create($invoiceId)
    {
        return InvoiceAmount::create([
            'invoice_id' => $invoiceId,
            'subtotal'   => 0,
            'tax'        => 0,
            'total'      => 0,
            'paid'       => 0,
            'balance'    => 0
        ]);
    }

    public function update($input, $invoiceId)
    {
        $invoiceAmount = InvoiceAmount::where('invoice_id', $invoiceId)->first();

        if ($invoiceAmount)
        {
            $invoiceAmount->fill($input);

            $invoiceAmount->save();
        }

        return $invoiceAmount;
    }
}