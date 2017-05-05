<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\Invoices\Validators\InvoiceValidator;
use FI\Support\Frequency;

class InvoiceCreateController extends Controller
{
    public function __construct(
        ClientRepository $clientRepository,
        GroupRepository $groupRepository,
        InvoiceRepository $invoiceRepository,
        InvoiceValidator $invoiceValidator
    )
    {
        parent::__construct();

        $this->clientRepository  = $clientRepository;
        $this->groupRepository   = $groupRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceValidator  = $invoiceValidator;
    }

    public function create()
    {
        return view('invoices._modal_create')
            ->with('groups', $this->groupRepository->lists())
            ->with('frequencies', Frequency::lists());
    }

    public function store()
    {
        $validator = $this->invoiceValidator->getValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $client = $this->clientRepository->firstOrCreate(request('client_name'));

        $invoice = $this->invoiceRepository->create(request()->all(), $client);

        return response()->json(['success' => true, 'id' => $invoice->id], 200);
    }
}