<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Reports\Repositories;

use DB;
use FI\Modules\Invoices\Models\Invoice;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;

class ClientStatementReportRepository
{
    public function getResults($clientName, $fromDate, $toDate)
    {
        $results = [
            'subtotal' => 0,
            'tax'      => 0,
            'total'    => 0,
            'paid'     => 0,
            'balance'  => 0,
            'records'  => []
        ];

        $invoices = Invoice::with('items', 'client.currency', 'amount.invoice.currency')
            ->where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->whereHas('client', function ($query) use ($clientName)
            {
                $query->where('unique_name', $clientName);
            })
            ->orderBy('created_at')
            ->get();

        if ($invoices->count())
        {
            $client = $invoices->first()->client;

            foreach ($invoices as $invoice)
            {
                $results['records'][] = [
                    'formatted_created_at' => $invoice->formatted_created_at,
                    'number'               => $invoice->number,
                    'summary'              => $invoice->summary,
                    'subtotal'             => $invoice->amount->subtotal,
                    'tax'                  => $invoice->amount->tax,
                    'total'                => $invoice->amount->total,
                    'paid'                 => $invoice->amount->paid,
                    'balance'              => $invoice->amount->balance,
                    'formatted_subtotal'   => $invoice->amount->formatted_subtotal,
                    'formatted_tax'        => $invoice->amount->formatted_tax,
                    'formatted_total'      => $invoice->amount->formatted_total,
                    'formatted_paid'       => $invoice->amount->formatted_paid,
                    'formatted_balance'    => $invoice->amount->formatted_balance
                ];

                $results['subtotal'] += $invoice->amount->subtotal;
                $results['tax'] += $invoice->amount->tax;
                $results['total'] += $invoice->amount->total;
                $results['paid'] += $invoice->amount->paid;
                $results['balance'] += $invoice->amount->balance;
            }
        }

        $currency = (isset($client)) ? $client->currency : config('fi.currency');

        $results['client_name'] = $invoice->client->name;
        $results['from_date']   = DateFormatter::format($fromDate);
        $results['to_date']     = DateFormatter::format($toDate);
        $results['subtotal']    = CurrencyFormatter::format($results['subtotal'], $currency);
        $results['tax']         = CurrencyFormatter::format($results['tax'], $currency);
        $results['total']       = CurrencyFormatter::format($results['total'], $currency);
        $results['paid']        = CurrencyFormatter::format($results['paid'], $currency);
        $results['balance']     = CurrencyFormatter::format($results['balance'], $currency);

        return $results;
    }
}