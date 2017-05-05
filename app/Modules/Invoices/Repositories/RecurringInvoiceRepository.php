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

use FI\Modules\Invoices\Models\RecurringInvoice;
use FI\Events\InvoiceRecurring;
use FI\Support\DateFormatter;

class RecurringInvoiceRepository
{
    public function __construct(InvoiceCopyRepository $invoiceCopyRepository)
    {
        $this->invoiceCopyRepository = $invoiceCopyRepository;
    }

    public function paginate($filter = null)
    {
        $recurringInvoice = RecurringInvoice::select('recurring_invoices.*')
            ->join('invoices', 'invoices.id', '=', 'recurring_invoices.invoice_id')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->with('invoice.client');

        if ($filter)
        {
            $recurringInvoice->keywords($filter);
        }

        return $recurringInvoice->sortable(['generate_at' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function create($input, $invoiceId)
    {
        if (isset($input['generate_at']))
        {
            $generateAt = DateFormatter::unformat($input['generate_at']);
        }
        else
        {
            $generateAt = DateFormatter::incrementDate(DateFormatter::unformat($input['created_at']), $input['recurring_period'], $input['recurring_frequency']);
        }

        if (isset($input['recurring_stop_at']) and $input['recurring_stop_at'])
        {
            $recurringStopAt = DateFormatter::unformat($input['recurring_stop_at']);
        }
        else
        {
            $recurringStopAt = '0000-00-00';
        }

        return RecurringInvoice::create(
            ['invoice_id'          => $invoiceId,
             'recurring_frequency' => $input['recurring_frequency'],
             'recurring_period'    => $input['recurring_period'],
             'stop_at'             => $recurringStopAt,
             'generate_at'         => $generateAt]
        );
    }

    public function recurInvoices()
    {
        $recurringInvoices = RecurringInvoice::recurNow()->get();

        foreach ($recurringInvoices as $recurringInvoice)
        {
            $newInvoice = $this->invoiceCopyRepository->copyInvoice(
                $recurringInvoice->invoice_id,
                $recurringInvoice->invoice->client->name,
                $recurringInvoice->generate_at,
                DateFormatter::incrementDateByDays(substr($recurringInvoice->generate_at, 0, 10), config('fi.invoicesDueAfter')),
                $recurringInvoice->invoice->group_id,
                $recurringInvoice->invoice->user_id);

            if ($recurringInvoice->stop_at == '0000-00-00' or ($recurringInvoice->stop_at !== '0000-00-00' and ($recurringInvoice->generate_at < $recurringInvoice->stop_at)))
            {
                $generateAt = DateFormatter::incrementDate(substr($recurringInvoice->generate_at, 0, 10), $recurringInvoice->recurring_period, $recurringInvoice->recurring_frequency);
            }
            else
            {
                $generateAt = '0000-00-00';
            }

            $recurringInvoice->generate_at = $generateAt;
            $recurringInvoice->save();

            event(new InvoiceRecurring($newInvoice));
        }

        return count($recurringInvoices);
    }

    public function find($id)
    {
        return RecurringInvoice::with(['items.amount', 'custom'])->find($id);
    }

    public function delete($id)
    {
        RecurringInvoice::destroy($id);
    }

    public function stop($id)
    {
        $recurringInvoice              = RecurringInvoice::find($id);
        $recurringInvoice->stop_at     = date('Y-m-d');
        $recurringInvoice->generate_at = '0000-00-00';
        $recurringInvoice->save();
    }
}