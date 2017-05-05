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
use FI\Modules\Groups\Repositories\GroupRepository;
use FI\Modules\Quotes\Repositories\QuoteCopyRepository;
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Modules\Quotes\Validators\QuoteValidator;
use FI\Support\DateFormatter;

class QuoteCopyController extends Controller
{
    public function __construct(
        GroupRepository $groupRepository,
        QuoteCopyRepository $quoteCopyRepository,
        QuoteRepository $quoteRepository,
        QuoteValidator $quoteValidator
    )
    {
        parent::__construct();

        $this->groupRepository     = $groupRepository;
        $this->quoteCopyRepository = $quoteCopyRepository;
        $this->quoteRepository     = $quoteRepository;
        $this->quoteValidator      = $quoteValidator;
    }

    public function create()
    {
        $quote = $this->quoteRepository->find(request('quote_id'));

        return view('quotes._modal_copy_quote')
            ->with('quote', $quote)
            ->with('groups', $this->groupRepository->lists())
            ->with('created_at', DateFormatter::format())
            ->with('user_id', auth()->user()->id);
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

        $quoteId = $this->quoteCopyRepository->copyQuote(
            request('quote_id'),
            request('client_name'),
            DateFormatter::unformat(request('created_at')),
            DateFormatter::incrementDateByDays(DateFormatter::unformat(request('created_at')), config('fi.quotesExpireAfter')),
            request('group_id'),
            auth()->user()->id)->id;

        return response()->json(['success' => true, 'id' => $quoteId], 200);
    }
}