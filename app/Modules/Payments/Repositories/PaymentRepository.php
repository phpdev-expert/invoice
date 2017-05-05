<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Payments\Repositories;

use FI\Modules\Payments\Models\Payment;
use FI\Events\InvoiceModified;
use FI\Support\DateFormatter;
use FI\Support\NumberFormatter;

class PaymentRepository
{
    public function paginate($with = [], $filter = null, $clientId = null)
    {
        $payments = Payment::select('payments.*')
            ->with($with)
            ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id');

        if ($filter)
        {
            $payments->keywords($filter);
        }

        if ($clientId)
        {
            $payments->whereHas('invoice', function ($query) use ($clientId)
            {
                $query->where('client_id', $clientId);
            });
        }

        return $payments->sortable(['paid_at' => 'desc', 'payments.created_at' => 'desc'])->paginate(config('fi.resultsPerPage'));
    }

    public function getTotalPaidByInvoiceId($invoiceId)
    {
        return Payment::where('invoice_id', $invoiceId)->sum('amount');
    }

    public function getByClientId($clientId)
    {
        return Payment::whereHas('invoice', function ($query) use ($clientId)
        {
            $query->where('client_id', $clientId);
        })->orderBy('paid_at', 'desc')->get();
    }

    public function find($id, $with = [])
    {
        return Payment::with($with)->find($id);
    }

    public function findByInvoiceId($invoiceId)
    {
        return Payment::where('invoice_id', '=', $invoiceId)->get();
    }

    public function create($input)
    {
        $input['paid_at'] = (isset($input['paid_at'])) ? DateFormatter::unformat($input['paid_at']) : date('Y-m-d');
        $input['amount']  = NumberFormatter::unformat($input['amount']);

        $payment = Payment::create($input);

        event(new InvoiceModified($payment->invoice));

        return $payment;
    }

    public function update($input, $id)
    {
        $input['paid_at'] = (isset($input['paid_at'])) ? DateFormatter::unformat($input['paid_at']) : date('Y-m-d');
        $input['amount']  = NumberFormatter::unformat($input['amount']);

        $payment = Payment::find($id);

        $payment->fill($input);

        $payment->save();

        event(new InvoiceModified($payment->invoice));

        return $payment;
    }

    public function delete($id)
    {
        $payment = Payment::find($id);

        $invoice = $payment->invoice;

        $payment->delete();

        event(new InvoiceModified($invoice));
    }
}