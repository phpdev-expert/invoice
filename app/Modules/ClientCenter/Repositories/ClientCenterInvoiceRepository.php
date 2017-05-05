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

use FI\Modules\Invoices\Models\Invoice;
use Illuminate\Support\Facades\DB;

class ClientCenterInvoiceRepository
{
    public function paginate($clientId)
    {
        return Invoice::with(['amount.invoice.currency', 'client'])
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'DESC')
            ->orderBy(DB::raw('length(number)'), 'DESC')
            ->orderBy('number', 'DESC')
            ->paginate(config('fi.resultsPerPage'));
    }

    public function getRecent($clientId)
    {
        return Invoice::with(['amount.invoice.currency', 'client'])
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'DESC')
            ->orderBy(DB::raw('length(number)'), 'DESC')
            ->orderBy('number', 'DESC')
            ->limit(5)->get();
    }
}