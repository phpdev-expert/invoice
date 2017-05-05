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
use FI\Modules\Payments\Models\Payment;
use FI\Support\CurrencyFormatter;

class RevenueByClientReportRepository
{
    public function getDistinctYears()
    {
        $return = [];

        if (config('database.default' == 'sqlite'))
        {
            $years = Payment::select(DB::raw("strftime('%Y', paid_at) AS year"))->distinct()->orderBy(DB::raw("strftime('%Y', paid_at)"))->get();
        }
        else
        {
            $years = Payment::select(DB::raw("YEAR(paid_at) AS year"))->distinct()->orderBy(DB::raw("YEAR(paid_at)"))->get();
        }

        foreach ($years as $year)
        {
            $return[$year->year] = $year->year;
        }

        return $return;
    }

    public function getResults($year)
    {
        $results = [];

        $payments = Payment::with(['invoice.client'])->byYear($year)->join('invoices', 'invoices.id', '=', 'payments.invoice_id')->join('clients', 'clients.id', '=', 'invoices.client_id')->orderBy('clients.name')->get();

        foreach ($payments as $payment)
        {
            if (isset($results[$payment->invoice->client->name]['months'][date('n', strtotime($payment->paid_at))]))
            {
                $results[$payment->invoice->client->name]['months'][date('n', strtotime($payment->paid_at))] += $payment->amount / $payment->invoice->exchange_rate;
            }
            else
            {
                $results[$payment->invoice->client->name]['months'][date('n', strtotime($payment->paid_at))] = $payment->amount / $payment->invoice->exchange_rate;
            }
        }

        foreach ($results as $client => $result)
        {
            $results[$client]['total'] = 0;

            foreach (range(1, 12) as $month)
            {
                if (!isset($results[$client]['months'][$month]))
                {
                    $results[$client]['months'][$month] = CurrencyFormatter::format(0);
                }
                else
                {
                    $results[$client]['total'] += $results[$client]['months'][$month];
                    $results[$client]['months'][$month] = CurrencyFormatter::format($results[$client]['months'][$month]);
                }
            }
            $results[$client]['total'] = CurrencyFormatter::format($results[$client]['total']);
        }

        return $results;
    }
}