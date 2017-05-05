<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Merchant\Controllers;

use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\Merchant\Repositories\InvoiceTransactionRepository;
use FI\Modules\Payments\Repositories\PaymentRepository;
use FI\Events\PaymentCreated;
use FI\Http\Controllers\Controller;
use FI\Support\DateFormatter;

class MerchantController extends Controller
{
    public function __construct(
        InvoiceRepository $invoiceRepository,
        InvoiceTransactionRepository $invoiceTransactionRepository,
        PaymentRepository $paymentRepository
    )
    {
        parent::__construct();
        $this->invoiceRepository            = $invoiceRepository;
        $this->invoiceTransactionRepository = $invoiceTransactionRepository;
        $this->paymentRepository            = $paymentRepository;
    }

    public function invoicePay($urlKey)
    {
        // Get the selected merchant
        $merchant = request('merchant');

        // Path to the merchant library
        $merchantLib = '\\FI\\Modules\\Merchant\\Support\\Drivers\\' . $merchant;

        // Create the gateway
        $gateway = $merchantLib::createGateway();

        // Get the invoice
        $invoice = $this->invoiceRepository->findByUrlKey($urlKey);

        // Define some default purchase parameters and get any additional from
        // the merchant driver
        $purchaseParams = $merchantLib::setPurchaseParameters([
            'amount'      => $invoice->amount->balance,
            'description' => trans('fi.invoice') . ' #' . $invoice->number,
            'currency'    => $invoice->currency_code,
        ], ['urlKey' => $urlKey, 'post' => $_POST]);

        // Get the response
        $response = $gateway->purchase($purchaseParams)->send();

        // Handle the response accordingly
        if ($response->isSuccessful())
        {
            // This was a successful on-site transaction.
            // Record the transaction.
            $this->recordSuccessTransaction($response, $invoice);

            // Redirect back to the client invoice
            return redirect()->route('clientCenter.public.invoice.show', [$urlKey])->with('alertSuccess', trans('fi.payment_applied'));
        }
        elseif ($response->isRedirect())
        {
            // This is an off-site transaction. Redirect off-site.
            $response->redirect();
        }
        else
        {
            // Record the failed transaction
            $this->recordFailTransaction($response, $invoice);

            return redirect()->route('clientCenter.public.invoice.show', [$urlKey])->with('error', $response->getMessage());
        }

    }

    public function invoiceReturn($urlKey)
    {
        // Get the selected merchant
        $merchant = request('merchant');

        // Path to the merchant library
        $merchantLib = '\\FI\\Modules\\Merchant\\Support\\Drivers\\' . $merchant;

        if ($merchantLib::isNotify())
        {
            // Redirect back to the client invoice
            return redirect()->route('clientCenter.public.invoice.show', [$urlKey]);
        }
        else
        {
            // Create the gateway
            $gateway = $merchantLib::createGateway();

            // Get the invoice
            $invoice = $this->invoiceRepository->findByUrlKey($urlKey);

            // Define some default purchase parameters and get any additional from
            // the merchant driver
            $purchaseParams = $merchantLib::setPurchaseParameters([
                'amount'      => $invoice->amount->balance,
                'description' => trans('fi.invoice') . ' #' . $invoice->number,
                'currency'    => $invoice->currency_code
            ], ['urlKey' => $urlKey, 'post' => $_POST]);

            // Get the response
            $response = $gateway->completePurchase($purchaseParams)->send();

            // Handle the response accordingly
            if ($response->isSuccessful())
            {
                // Record the successful transaction
                $this->recordSuccessTransaction($response, $invoice);

                // Redirect back to the client invoice
                return redirect()->route('clientCenter.public.invoice.show', [$urlKey])->with('alertSuccess', trans('fi.payment_applied'));
            }
            else
            {
                // Record the failed transaction
                $this->recordFailTransaction($response, $invoice);

                return redirect()->route('clientCenter.public.invoice.show', [$urlKey])->with('error', $response->getMessage());
            }
        }
    }

    public function invoiceNotify($urlKey)
    {
        // Get the selected merchant
        $merchant = request('merchant');

        // Path to the merchant library
        $merchantLib = '\\FI\\Modules\\Merchant\\Support\\Drivers\\' . $merchant;

        // Create the gateway
        $gateway = $merchantLib::createGateway();

        // Get the invoice
        $invoice = $this->invoiceRepository->findByUrlKey($urlKey);

        // Define some default purchase parameters and get any additional from
        // the merchant driver
        $purchaseParams = $merchantLib::setPurchaseParameters([
            'amount'      => $invoice->amount->balance,
            'description' => trans('fi.invoice') . ' #' . $invoice->number,
            'currency'    => $invoice->currency_code
        ], ['urlKey' => $urlKey, 'post' => $_POST]);

        // Get the response
        $response = $gateway->completePurchase($purchaseParams)->send();

        // Handle the response accordingly
        if ($response->isSuccessful())
        {
            // Record the successful transaction
            $this->recordSuccessTransaction($response, $invoice);
        }
        else
        {
            // Record the failed transaction
            $this->recordFailTransaction($response, $invoice);
        }
    }

    public function invoiceCancel($urlKey)
    {
        // Redirect back to the client invoice
        return redirect()->route('clientCenter.public.invoice.show', [$urlKey]);
    }

    public function recordSuccessTransaction($response, $invoice)
    {
        $requestParameters = $response->getRequest()->getParameters();

        $this->invoiceTransactionRepository->create(['invoice_id' => $invoice->id, 'is_successful' => 1, 'transaction_reference' => $response->getTransactionReference()]);

        $payment = $this->paymentRepository->create(['invoice_id' => $invoice->id, 'payment_method_id' => (config('fi.onlinePaymentMethod') ?: 0), 'amount' => $requestParameters['amount'], 'paid_at' => DateFormatter::format(date('Y-m-d')), 'note' => '']);

        event(new PaymentCreated($payment));

        $invoice->activities()->create(['activity' => 'public.paid']);
    }

    public function recordFailTransaction($response, $invoice)
    {
        $this->invoiceTransactionRepository->create(['invoice_id' => $invoice->id, 'is_successful' => 0, 'transaction_reference' => $response->getMessage()]);
    }

    public function invoiceModalCc($urlKey)
    {
        return view('merchant/_modal_' . strtolower(request('merchant')))
            ->with('invoice', $this->invoiceRepository->findByUrlKey($urlKey))
            ->with('merchant', json_decode(config()->get('fi.merchant')));
    }
}