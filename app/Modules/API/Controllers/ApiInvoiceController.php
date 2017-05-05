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

use FI\Events\InvoiceModified;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Invoices\Repositories\InvoiceItemRepository;
use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\Invoices\Validators\InvoiceValidator;
use FI\Modules\Users\Repositories\UserRepository;
use FI\Validators\ItemValidator;

class ApiInvoiceController extends ApiController
{
    public function __construct(
        ClientRepository $clientRepository,
        InvoiceItemRepository $invoiceItemRepository,
        InvoiceRepository $invoiceRepository,
        InvoiceValidator $invoiceValidator,
        ItemValidator $itemValidator,
        UserRepository $userRepository
    )
    {
        parent::__construct();
        $this->clientRepository      = $clientRepository;
        $this->invoiceItemRepository = $invoiceItemRepository;
        $this->invoiceRepository     = $invoiceRepository;
        $this->invoiceValidator      = $invoiceValidator;
        $this->itemValidator         = $itemValidator;
        $this->userRepository        = $userRepository;
    }

    public function lists()
    {
        return response()->json($this->invoiceRepository->with(['items.amount', 'client', 'amount', 'currency'])->paginateByStatus(request('status')));
    }

    public function show()
    {
        return response()->json($this->invoiceRepository->with(['items.amount', 'client', 'amount', 'currency'])->find(request('id')));
    }

    public function create()
    {
        $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

        $input['user_id'] = $this->userRepository->findByApiPublicKey(request('key'))->id;

        $validator = $this->invoiceValidator->getValidator($input);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        $client = $this->clientRepository->firstOrCreate(request('client_name'));

        return response()->json($this->invoiceRepository->create($input, $client));
    }

    public function addItem()
    {
        $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

        $itemValidator = $this->itemValidator->getApiInvoiceValidator($input);

        if ($itemValidator->fails())
        {
            return response()->json(['errors' => $itemValidator->errors()], 400);
        }

        $this->invoiceItemRepository->saveItems([$input]);

        event(new InvoiceModified($this->invoiceRepository->find($input['invoice_id'])));
    }

    public function delete()
    {
        $validator = $this->validator->make(request()->only('id'), ['id' => 'required']);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        if ($this->invoiceRepository->find(request('id')))
        {
            $this->invoiceRepository->delete(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}