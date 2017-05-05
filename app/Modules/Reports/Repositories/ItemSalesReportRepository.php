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

use FI\Modules\Invoices\Models\InvoiceItem;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\NumberFormatter;

class ItemSalesReportRepository
{
    public function getResults($fromDate, $toDate)
    {
        $results = array();

        $items = InvoiceItem::byDateRange($fromDate, $toDate)
            ->select('invoice_items.name AS item_name', 'invoice_items.quantity AS item_quantity',
                'invoice_items.price AS item_price', 'clients.name AS client_name', 'invoices.number AS invoice_number',
                'invoices.created_at AS invoice_created_at', 'invoices.exchange_rate AS invoice_exchange_rate',
                'invoice_item_amounts.subtotal', 'invoice_item_amounts.tax', 'invoice_item_amounts.total')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('invoice_item_amounts', 'invoice_item_amounts.item_id', '=', 'invoice_items.id')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->orderBy('invoice_items.name')->get();

        foreach ($items as $item)
        {
            $results[$item->item_name]['items'][] = array(
                'client_name'    => $item->client_name,
                'invoice_number' => $item->invoice_number,
                'date'           => DateFormatter::format($item->invoice_created_at),
                'price'          => CurrencyFormatter::format($item->item_price / $item->invoice_exchange_rate),
                'quantity'       => NumberFormatter::format($item->item_quantity),
                'subtotal'       => CurrencyFormatter::format($item->subtotal / $item->invoice_exchange_rate),
                'tax'            => CurrencyFormatter::format($item->tax_total / $item->invoice_exchange_rate),
                'total'          => CurrencyFormatter::format($item->total / $item->invoice_exchange_rate)
            );

            if (isset($results[$item->item_name]['totals']))
            {
                $results[$item->item_name]['totals']['quantity'] += $item->quantity;
                $results[$item->item_name]['totals']['subtotal'] += round($item->subtotal / $item->invoice_exchange_rate, 2);
                $results[$item->item_name]['totals']['tax'] += round($item->tax_total / $item->invoice_exchange_rate, 2);
                $results[$item->item_name]['totals']['total'] += round($item->total / $item->invoice_exchange_rate, 2);
            }
            else
            {
                $results[$item->item_name]['totals']['quantity'] = $item->quantity;
                $results[$item->item_name]['totals']['subtotal'] = round($item->subtotal / $item->invoice_exchange_rate, 2);
                $results[$item->item_name]['totals']['tax']      = round($item->tax_total / $item->invoice_exchange_rate, 2);
                $results[$item->item_name]['totals']['total']    = round($item->total / $item->invoice_exchange_rate, 2);
            }
        }

        foreach ($results as $key => $result)
        {
            $results[$key]['totals']['quantity'] = NumberFormatter::format($results[$key]['totals']['quantity']);
            $results[$key]['totals']['subtotal'] = CurrencyFormatter::format($results[$key]['totals']['subtotal']);
            $results[$key]['totals']['tax']      = CurrencyFormatter::format($results[$key]['totals']['tax']);
            $results[$key]['totals']['total']    = CurrencyFormatter::format($results[$key]['totals']['total']);
        }

        return $results;
    }
}