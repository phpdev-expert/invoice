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

use FI\Events\QuoteModified;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Quotes\Repositories\QuoteItemRepository;
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Modules\Quotes\Validators\QuoteValidator;
use FI\Modules\Users\Repositories\UserRepository;
use FI\Validators\ItemValidator;

class ApiQuoteController extends ApiController
{
    public function __construct(
        ClientRepository $clientRepository,
        QuoteItemRepository $quoteItemRepository,
        QuoteRepository $quoteRepository,
        QuoteValidator $quoteValidator,
        ItemValidator $itemValidator,
        UserRepository $userRepository
    )
    {
        parent::__construct();
        $this->clientRepository    = $clientRepository;
        $this->quoteItemRepository = $quoteItemRepository;
        $this->quoteRepository     = $quoteRepository;
        $this->quoteValidator      = $quoteValidator;
        $this->itemValidator       = $itemValidator;
        $this->userRepository      = $userRepository;
    }

    public function lists()
    {
        return response()->json($this->quoteRepository->with(['items.amount', 'client', 'amount', 'currency'])->paginateByStatus(request('status')));
    }

    public function show()
    {
        return response()->json($this->quoteRepository->with(['items.amount', 'client', 'amount', 'currency'])->find(request('id')));
    }

    public function create()
    {
        $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

        $input['user_id'] = $this->userRepository->findByApiPublicKey(request('key'))->id;

        $validator = $this->quoteValidator->getValidator($input);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        $client = $this->clientRepository->firstOrCreate(request('client_name'));

        return response()->json($this->quoteRepository->create($input, $client));
    }

    public function addItem()
    {
        $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

        $itemValidator = $this->itemValidator->getApiQuoteValidator($input);

        if ($itemValidator->fails())
        {
            return response()->json(['errors' => $itemValidator->errors()], 400);
        }

        $this->quoteItemRepository->saveItems([$input]);

        event(new QuoteModified($this->quoteRepository->find($input['quote_id'])));
    }

    public function delete()
    {
        $validator = $this->validator->make(request()->only('id'), ['id' => 'required']);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        if ($this->quoteRepository->find(request('id')))
        {
            $this->quoteRepository->delete(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}