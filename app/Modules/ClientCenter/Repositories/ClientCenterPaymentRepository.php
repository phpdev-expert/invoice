<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ClientCenter\Repositories;

use FI\Modules\Payments\Models\Payment;

class ClientCenterPaymentRepository
{
    public function paginate($clientId)
    {
        return Payment::with('invoice.amount.invoice.currency', 'invoice.client')
            ->whereHas('invoice', function ($invoice) use ($clientId)
            {
                $invoice->where('client_id', $clientId);
            })->orderBy('created_at', 'desc')
            ->paginate(config('fi.resultsPerPage'));
    }

    public function getRecent($clientId)
    {
        return Payment::with('invoice.amount.invoice.currency', 'invoice.client')
            ->whereHas('invoice', function ($invoice) use ($clientId)
            {
                $invoice->where('client_id', $clientId);
            })->orderBy('created_at', 'desc')
            ->limit(5)->get();
    }
}