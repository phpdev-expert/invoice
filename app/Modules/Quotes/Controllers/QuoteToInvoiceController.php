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
use FI\Modules\Quotes\Repositories\QuoteInvoiceRepository;
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Modules\Quotes\Validators\QuoteValidator;
use FI\Support\DateFormatter;

class QuoteToInvoiceController extends Controller
{
    public function __construct(
        GroupRepository $groupRepository,
        QuoteInvoiceRepository $quoteInvoiceRepository,
        QuoteRepository $quoteRepository,
        QuoteValidator $quoteValidator
    )
    {
        parent::__construct();

        $this->groupRepository        = $groupRepository;
        $this->quoteInvoiceRepository = $quoteInvoiceRepository;
        $this->quoteRepository        = $quoteRepository;
        $this->quoteValidator         = $quoteValidator;
    }

    public function create()
    {
        return view('quotes._modal_quote_to_invoice')
            ->with('quote_id', request('quote_id'))
            ->with('client_id', request('client_id'))
            ->with('groups', $this->groupRepository->lists())
            ->with('user_id', auth()->user()->id)
            ->with('created_at', DateFormatter::format());
    }

    public function store()
    {
        $input = request()->all();

        $validator = $this->quoteValidator->getToInvoiceValidator($input);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $quote = $this->quoteRepository->find($input['quote_id']);

        $invoice = $this->quoteInvoiceRepository->quoteToInvoice(
            $quote,
            DateFormatter::unformat($input['created_at']),
            DateFormatter::incrementDateByDays(DateFormatter::unformat($input['created_at']), config('fi.invoicesDueAfter')),
            $input['group_id']
        );

        return response()->json(['success' => true, 'redirectTo' => route('invoices.edit', ['invoice' => $invoice->id])], 200);
    }
}