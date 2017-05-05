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

use FI\Modules\Invoices\Models\InvoiceItemAmount;

class InvoiceItemAmountRepository
{
    public function update($input, $itemId)
    {
        $invoiceItemAmount = InvoiceItemAmount::where('item_id', $itemId)->first();

        $invoiceItemAmount->fill($input);

        $invoiceItemAmount->save();

        return $invoiceItemAmount;
    }

    public function deleteByItemId($itemId)
    {
        InvoiceItemAmount::where('item_id', $itemId)->delete();
    }
}