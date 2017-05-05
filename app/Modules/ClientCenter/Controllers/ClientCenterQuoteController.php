<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ClientCenter\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\ClientCenter\Repositories\ClientCenterQuoteRepository;
use FI\Support\Statuses\QuoteStatuses;

class ClientCenterQuoteController extends Controller
{
    public function __construct(ClientCenterQuoteRepository $clientCenterQuoteRepository, QuoteStatuses $quoteStatuses)
    {
        parent::__construct();
        $this->clientCenterQuoteRepository = $clientCenterQuoteRepository;
        $this->quoteStatuses               = $quoteStatuses;
        $this->clientId                    = auth()->user()->client->id;
    }

    public function index()
    {
        return view('client_center.quotes.index')
            ->with('quotes', $this->clientCenterQuoteRepository->paginate($this->clientId))
            ->with('quoteStatuses', $this->quoteStatuses->statuses());
    }
}