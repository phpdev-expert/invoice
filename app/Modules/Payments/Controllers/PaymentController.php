<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Payments\Controllers;

use FI\Events\PaymentCreated;
use FI\Http\Controllers\Controller;
use FI\Modules\CustomFields\Repositories\CustomFieldRepository;
use FI\Modules\CustomFields\Repositories\PaymentCustomRepository;
use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\PaymentMethods\Repositories\PaymentMethodRepository;
use FI\Modules\Payments\Repositories\PaymentRepository;
use FI\Modules\Payments\Validators\PaymentValidator;
use FI\Support\DateFormatter;
use Mail;

class PaymentController extends Controller
{
    public function __construct(
        CustomFieldRepository $customFieldRepository,
        InvoiceRepository $invoiceRepository,
        PaymentCustomRepository $paymentCustomRepository,
        PaymentMethodRepository $paymentMethodRepository,
        PaymentRepository $paymentRepository,
        PaymentValidator $paymentValidator)
    {
        parent::__construct();
        $this->customFieldRepository   = $customFieldRepository;
        $this->invoiceRepository       = $invoiceRepository;
        $this->paymentCustomRepository = $paymentCustomRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentRepository       = $paymentRepository;
        $this->validator               = $paymentValidator;
    }

    public function index()
    {
        $payments = $this->paymentRepository->paginate(
            ['invoice.client', 'invoice.currency', 'paymentMethod'],
            request('search'),
            request('client')
        );

        return view('payments.index')
            ->with('payments', $payments)
            ->with('displaySearch', true);
    }

    public function edit($paymentId, $invoiceId)
    {
        $payment = $this->paymentRepository->find($paymentId);
        $invoice = $this->invoiceRepository->find($invoiceId);

        return view('payments.form')
            ->with('editMode', true)
            ->with('payment', $payment)
            ->with('paymentMethods', $this->paymentMethodRepository->lists())
            ->with('invoice', $invoice)
            ->with('customFields', $this->customFieldRepository->getByTable('payments'));
    }

    public function update($paymentId, $invoiceId)
    {
        $input = request()->all();

        if (request()->has('custom'))
        {
            $custom = $input['custom'];
            unset($input['custom']);
        }

        $validator = $this->validator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('payments.edit', [$paymentId, $invoiceId])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->paymentRepository->update($input, $paymentId);

        if (request()->has('custom'))
        {
            $this->paymentCustomRepository->save($custom, $paymentId);
        }

        return redirect()->route('payments.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($paymentId, $invoiceId)
    {
        $this->paymentRepository->delete($paymentId);

        return redirect()->route('payments.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function modalEnterPayment()
    {
        $date = DateFormatter::format();

        return view('payments._modal_enter_payment')
            ->with('invoice_id', request('invoice_id'))
            ->with('balance', $this->invoiceRepository->find(request('invoice_id'))->amount->formatted_numeric_balance)
            ->with('date', $date)
            ->with('paymentMethods', $this->paymentMethodRepository->lists())
            ->with('customFields', $this->customFieldRepository->getByTable('payments'))
            ->with('redirectTo', request('redirectTo'));
    }

    public function ajaxStore()
    {
        // Validate the input and return correct response
        $input = request()->all();

        $custom = (array)json_decode($input['custom']);

        unset($input['custom'], $input['email_payment_receipt']);

        $validator = $this->validator->getValidator($input);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $payment = $this->paymentRepository->create($input);

        $this->paymentCustomRepository->save($custom, $payment->id);

        if (request('email_payment_receipt') == 'true')
        {
            event(new PaymentCreated($payment, false));
        }

        return response()->json(['success' => true], 200);
    }
}