<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\PaymentMethods\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\PaymentMethods\Repositories\PaymentMethodRepository;
use FI\Modules\PaymentMethods\Validators\PaymentMethodValidator;

class PaymentMethodController extends Controller
{
    public function __construct(
        PaymentMethodRepository $paymentMethodRepository,
        PaymentMethodValidator $paymentMethodValidator
    )
    {
        parent::__construct();
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodValidator  = $paymentMethodValidator;
    }

    public function index()
    {
        $paymentMethods = $this->paymentMethodRepository->paginate();

        return view('payment_methods.index')
            ->with('paymentMethods', $paymentMethods);
    }

    public function create()
    {
        return view('payment_methods.form')
            ->with('editMode', false);
    }

    public function store()
    {
        $input = request()->all();

        $validator = $this->paymentMethodValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('paymentMethods.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $this->paymentMethodRepository->create($input);

        return redirect()->route('paymentMethods.index')
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function edit($id)
    {
        $paymentMethod = $this->paymentMethodRepository->find($id);

        return view('payment_methods.form')
            ->with(['editMode' => true, 'paymentMethod' => $paymentMethod]);
    }

    public function update($id)
    {
        $input = request()->all();

        $validator = $this->paymentMethodValidator->getValidator($input);

        if ($validator->fails())
        {
            return redirect()->route('paymentMethods.edit', [$id])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->paymentMethodRepository->update($input, $id);

        return redirect()->route('paymentMethods.index')
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($id)
    {
        $this->paymentMethodRepository->delete($id);

        return redirect()->route('paymentMethods.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }
}