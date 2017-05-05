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

use FI\Modules\Invoices\Models\Invoice;
use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;

class TaxSummaryReportRepository
{
    public function getResults($fromDate, $toDate)
    {
        $results = [];

        $invoices = Invoice::with(['items.taxRate', 'items.taxRate2', 'items.amount'])->where('created_at', '>=', $fromDate)->where('created_at', '<=', $toDate)->get();

        foreach ($invoices as $invoice)
        {
            foreach ($invoice->items as $invoiceItem)
            {
                if ($invoiceItem->tax_rate_id)
                {
                    $key = $invoiceItem->taxRate->name . ' (' . NumberFormatter::format($invoiceItem->taxRate->percent, null, 3) . '%)';

                    if (isset($results[$key]['taxable_amount']))
                    {
                        $results[$key]['taxable_amount'] += $invoiceItem->amount->subtotal / $invoice->exchange_rate;
                        $results[$key]['taxes'] += $invoiceItem->amount->tax_1 / $invoice->exchange_rate;
                    }
                    else
                    {
                        $results[$key]['taxable_amount'] = $invoiceItem->amount->subtotal / $invoice->exchange_rate;
                        $results[$key]['taxes']          = $invoiceItem->amount->tax_1 / $invoice->exchange_rate;
                    }
                }

                if ($invoiceItem->tax_rate_2_id)
                {
                    $key = $invoiceItem->taxRate2->name . ' (' . NumberFormatter::format($invoiceItem->taxRate2->percent, null, 3) . '%)';

                    if (isset($results[$key]['taxable_amount']))
                    {
                        if ($invoiceItem->taxRate2->is_compound)
                        {
                            $results[$key]['taxable_amount'] += ($invoiceItem->amount->subtotal + $invoiceItem->amount->tax_1) / $invoice->exchange_rate;
                        }
                        else
                        {
                            $results[$key]['taxable_amount'] += $invoiceItem->amount->subtotal / $invoice->exchange_rate;
                        }

                        $results[$key]['taxes'] += $invoiceItem->amount->tax_2 / $invoice->exchange_rate;
                    }
                    else
                    {
                        if ($invoiceItem->taxRate2->is_compound)
                        {
                            $results[$key]['taxable_amount'] = ($invoiceItem->amount->subtotal + $invoiceItem->amount->tax_2) / $invoice->exchange_rate;
                        }
                        else
                        {
                            $results[$key]['taxable_amount'] = $invoiceItem->amount->subtotal / $invoice->exchange_rate;
                        }

                        $results[$key]['taxes'] = $invoiceItem->amount->tax_2 / $invoice->exchange_rate;
                    }
                }

            }

        }

        foreach ($results as $key => $result)
        {
            $results[$key]['taxable_amount'] = CurrencyFormatter::format($result['taxable_amount']);
            $results[$key]['taxes']          = CurrencyFormatter::format($result['taxes']);
        }

        return $results;
    }
}