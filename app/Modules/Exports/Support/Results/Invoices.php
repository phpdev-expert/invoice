<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Exports\Support\Results;

use FI\Modules\Invoices\Models\Invoice;

class Invoices implements SourceInterface
{
    public function getResults($params = [])
    {
        $invoice = Invoice::select('invoices.number', 'invoices.created_at', 'invoices.updated_at', 'invoices.due_at',
            'invoices.terms', 'invoices.footer', 'invoices.url_key', 'invoices.currency_code', 'invoices.exchange_rate',
            'invoices.template', 'invoices.summary', 'groups.name AS group', 'clients.name AS client_name',
            'clients.email AS client_email', 'clients.address AS client_address', 'clients.city AS client_city',
            'clients.state AS client_state', 'clients.zip AS client_zip', 'clients.country AS client_country',
            'users.name AS user_name', 'users.email AS user_email', 'users.company AS user_company',
            'users.address AS user_address', 'invoice_amounts.subtotal', 'invoice_amounts.tax', 'invoice_amounts.total',
            'invoice_amounts.paid', 'invoice_amounts.balance')
            ->join('invoice_amounts', 'invoice_amounts.invoice_id', '=', 'invoices.id')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->join('groups', 'groups.id', '=', 'invoices.group_id')
            ->join('users', 'users.id', '=', 'invoices.user_id')
            ->orderBy('number');

        return $invoice->get()->toArray();
    }
}