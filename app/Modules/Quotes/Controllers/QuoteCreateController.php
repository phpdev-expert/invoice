<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Modules\Quotes\Validators\QuoteValidator;

class QuoteCreateController extends Controller
{
    public function __construct(
        ClientRepository $clientRepository,
        GroupRepository $groupRepository,
        QuoteRepository $quoteRepository,
        QuoteValidator $quoteValidator
    )
    {
        parent::__construct();

        $this->clientRepository = $clientRepository;
        $this->groupRepository  = $groupRepository;
        $this->quoteRepository  = $quoteRepository;
        $this->quoteValidator   = $quoteValidator;
    }

    public function create()
    {
        return view('quotes._modal_create')
            ->with('groups', $this->groupRepository->lists());
    }

    public function store()
    {
        $validator = $this->quoteValidator->getValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $client = $this->clientRepository->firstOrCreate(request('client_name'));

        $quote = $this->quoteRepository->create(request()->all(), $client);

        return response()->json(['success' => true, 'id' => $quote->id], 200);
    }
}