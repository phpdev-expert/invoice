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
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\QuoteStatuses;

class QuoteController extends Controller
{
    public function __construct(QuoteRepository $quoteRepository)
    {
        parent::__construct();

        $this->quoteRepository = $quoteRepository;
    }

    public function index()
    {
        $status = (request('status')) ?: 'all';

        $quotes = $this->quoteRepository->with(['client', 'activities', 'amount.quote.currency'])
            ->paginateByStatus($status, request('search'), request('client'));

        $statuses = QuoteStatuses::statuses();

        return view('quotes.index')
            ->with('quotes', $quotes)
            ->with('status', $status)
            ->with('statuses', $statuses)
            ->with('displaySearch', true);
    }

    public function delete($id)
    {
        $this->quoteRepository->delete($id);

        return redirect()->route('quotes.index')
            ->with('alert', trans('fi.record_successfully_deleted'));
    }

    public function pdf($id)
    {
        $quote = $this->quoteRepository->find($id);

        $pdf = PDFFactory::create();

        $pdf->download($quote->html, FileNames::quote($quote));
    }
}