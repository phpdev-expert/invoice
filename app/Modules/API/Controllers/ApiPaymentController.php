<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Api\Controllers;

use FI\Modules\Payments\Repositories\PaymentRepository;
use FI\Modules\Payments\Validators\PaymentValidator;

class ApiPaymentController extends ApiController
{
    public function __construct(PaymentRepository $paymentRepository, PaymentValidator $paymentValidator)
    {
        parent::__construct();
        $this->paymentRepository = $paymentRepository;
        $this->paymentValidator  = $paymentValidator;
    }

    public function lists()
    {
        return response()->json($this->paymentRepository->paginate());
    }

    public function show()
    {
        if ($payment = $this->paymentRepository->find(request('id')))
        {
            return response()->json($payment);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }

    public function create()
    {
        $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

        $validator = $this->paymentValidator->getValidator($input);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        return response()->json($this->paymentRepository->create($input));
    }

    public function delete()
    {
        $validator = $this->validator->make(request()->only('id'), ['id' => 'required']);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        if ($this->paymentRepository->find(request('id')))
        {
            $this->paymentRepository->delete(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}