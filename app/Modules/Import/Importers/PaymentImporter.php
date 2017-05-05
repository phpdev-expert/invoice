<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Import\Importers;

use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\PaymentMethods\Repositories\PaymentMethodRepository;
use FI\Modules\Payments\Repositories\PaymentRepository;
use FI\Modules\Payments\Validators\PaymentValidator;

class PaymentImporter extends AbstractImporter
{
    public function __construct(
        InvoiceRepository $invoiceRepository,
        PaymentMethodRepository $paymentMethodRepository,
        PaymentRepository $paymentRepository,
        PaymentValidator $paymentValidator
    )
    {
        $this->invoiceRepository       = $invoiceRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentRepository       = $paymentRepository;
        $this->paymentValidator        = $paymentValidator;
    }

    public function getFields()
    {
        return [
            'paid_at'           => '* ' . trans('fi.date'),
            'invoice_id'        => '* ' . trans('fi.invoice_number'),
            'amount'            => '* ' . trans('fi.amount'),
            'payment_method_id' => '* ' . trans('fi.payment_method'),
            'note'              => trans('fi.note')
        ];
    }

    public function getMapRules()
    {
        return [
            'paid_at'           => 'required',
            'invoice_id'        => 'required',
            'amount'            => 'required',
            'payment_method_id' => 'required'
        ];
    }

    public function getValidator($input)
    {
        return $this->paymentValidator->getValidator($input);
    }

    public function importData($input)
    {
        $row = 1;

        $fields = [];

        foreach ($input as $field => $key)
        {
            if (is_numeric($key))
            {
                $fields[$key] = $field;
            }
        }

        try
        {
            $handle = fopen(storage_path('payments.csv'), 'r');
        }

        catch (\ErrorException $e)
        {
            $this->messages->add('error', 'Could not open the file');

            return false;
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== false)
        {
            if ($row !== 1)
            {
                $record = [];

                foreach ($fields as $key => $field)
                {
                    $record[$field] = $data[$key];
                }

                // Attempt to format the date, otherwise use today
                if (strtotime($record['paid_at']))
                {
                    $record['paid_at'] = date('Y-m-d', strtotime($record['paid_at']));
                }
                else
                {
                    $record['paid_at'] = date('Y-m-d');
                }

                // Transform the invoice number to the id
                $record['invoice_id'] = $this->invoiceRepository->findIdByNumber($record['invoice_id']);

                // Transform the payment method to the id
                if ($record['payment_method_id'] <> 'NULL')
                {
                    $record['payment_method_id'] = $this->paymentMethodRepository->firstOrCreate($record['payment_method_id'])->id;
                }
                else
                {
                    $record['payment_method_id'] = $this->paymentMethodRepository->firstOrCreate('Other')->id;
                }

                if (!isset($record['note']))
                {
                    $record['note'] = '';
                }

                if ($this->validateRecord($record))
                {
                    $this->paymentRepository->create($record);
                }
            }
            $row++;
        }

        fclose($handle);

        return true;
    }
}