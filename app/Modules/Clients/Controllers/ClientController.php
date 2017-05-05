<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Clients\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Clients\Validators\ClientValidator;
use FI\Modules\CustomFields\Repositories\ClientCustomRepository;
use FI\Modules\CustomFields\Repositories\CustomFieldRepository;
use FI\Modules\Payments\Repositories\PaymentRepository;
use FI\Support\BackPath;

class ClientController extends Controller
{
    public function __construct(
        ClientRepository $clientRepository,
        ClientCustomRepository $clientCustomRepository,
        CustomFieldRepository $customFieldRepository,
        ClientValidator $clientValidator,
        PaymentRepository $paymentRepository
    )
    {
        parent::__construct();

        $this->clientRepository       = $clientRepository;
        $this->clientCustomRepository = $clientCustomRepository;
        $this->customFieldRepository  = $customFieldRepository;
        $this->clientValidator        = $clientValidator;
        $this->paymentRepository      = $paymentRepository;
    }

    public function index()
    {
        $status = (request('status')) ?: 'all';

        $clients = $this->clientRepository->paginate($status, request('search'));

        return view('clients.index')
            ->with('clients', $clients)
            ->with('status', $status)
            ->with('displaySearch', true);
    }

    public function create()
    {
        return view('clients.form')
            ->with('editMode', false)
            ->with('customFields', $this->customFieldRepository->getByTable('clients'));
    }

    public function store()
    {
        $input = request()->all();

        if (request()->has('custom'))
        {
            $custom = $input['custom'];
            unset($input['custom']);
        }

        $validator = $this->clientValidator->getValidator($input);

        if ($validator->fails($input))
        {
            return redirect()->route('clients.create')
                ->with('editMode', false)
                ->withErrors($validator)
                ->withInput();
        }

        $clientId = $this->clientRepository->create($input)->id;

        if (request()->has('custom'))
        {
            $this->clientCustomRepository->save($custom, $clientId);
        }

        return redirect()->to(BackPath::getBackPath('clients'))
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }

    public function show($clientId)
    {
        $client = $this->clientRepository->find($clientId);

        $invoices = $client->invoices()
            ->with(['client', 'activities', 'amount.invoice.currency'])
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->take(config('fi.resultsPerPage'))->get();

        $quotes = $client->quotes()
            ->with(['client', 'activities', 'amount.quote.currency'])
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->take(config('fi.resultsPerPage'))->get();

        return view('clients.view')
            ->with('client', $client)
            ->with('invoices', $invoices)
            ->with('quotes', $quotes)
            ->with('payments', $this->paymentRepository->getByClientId($clientId))
            ->with('customFields', $this->customFieldRepository->getByTable('clients'));
    }

    public function edit($clientId)
    {
        $client = $this->clientRepository->with(['custom'])->find($clientId);

        return view('clients.form')
            ->with('editMode', true)
            ->with('client', $client)
            ->with('customFields', $this->customFieldRepository->getByTable('clients'));
    }

    public function update($clientId)
    {
        $input = request()->all();

        if (request()->has('custom'))
        {
            $custom = $input['custom'];
            unset($input['custom']);
        }

        $validator = $this->clientValidator->getUpdateValidator($input, $this->clientRepository->find($clientId));

        if ($validator->fails($input))
        {
            return redirect()->route('clients.edit', [$clientId])
                ->with('editMode', true)
                ->withErrors($validator)
                ->withInput();
        }

        $this->clientRepository->update($input, $clientId);

        if (request()->has('custom'))
        {
            $this->clientCustomRepository->save($custom, $clientId);
        }

        return redirect()->to(BackPath::getBackPath('clients'))
            ->with('alertInfo', trans('fi.record_successfully_updated'));
    }

    public function delete($clientId)
    {
        $this->clientRepository->delete($clientId);

        return redirect()->route('clients.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function ajaxLookup()
    {
        return $this->clientRepository->lookupByListIdentifier(request('query'));
    }

    public function ajaxModalEdit()
    {
        return view('clients._modal_edit')
            ->with('editMode', true)
            ->with('client', $this->clientRepository->find(request('client_id')))
            ->with('refreshToRoute', request('refresh_to_route'))
            ->with('id', request('id'))
            ->with('customFields', $this->customFieldRepository->getByTable('clients'));
    }

    public function ajaxModalUpdate($clientId)
    {
        $input = request()->all();

        if (request()->has('custom'))
        {
            $custom = $input['custom'];
            unset($input['custom']);
        }

        $validator = $this->clientValidator->getUpdateValidator($input, $this->clientRepository->find($clientId));

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $this->clientRepository->update($input, $clientId);

        if (request()->has('custom'))
        {
            $this->clientCustomRepository->save($custom, $clientId);
        }

        return response()->json(['success' => true], 200);
    }

    public function ajaxModalLookup()
    {
        return view('clients._modal_lookup')
            ->with('updateClientIdRoute', request('update_client_id_route'))
            ->with('refreshToRoute', request('refresh_to_route'))
            ->with('id', request('id'));
    }

    public function ajaxCheckName()
    {
        $clientId = $this->clientRepository->findIdByListIdentifier(request('client_name'));

        if ($clientId)
        {
            return response()->json(['success' => true, 'client_id' => $clientId], 200);
        }

        return response()->json([
            'success' => false,
            'errors'  => ['messages' => [trans('fi.client_not_found')]]
        ], 400);
    }

    public function ajaxCheckDuplicateName()
    {
        if ($this->clientRepository->nameIsDuplicate(request('client_name'), request('unique_name'), request('client_id')))
        {
            return response()->json(['is_duplicate' => 1]);
        }

        return response()->json(['is_duplicate' => 0]);
    }
}