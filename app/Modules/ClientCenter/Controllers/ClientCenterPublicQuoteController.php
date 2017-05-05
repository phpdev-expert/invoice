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

use FI\Events\QuoteApproved;
use FI\Events\QuoteRejected;
use FI\Events\QuoteViewed;
use FI\Http\Controllers\Controller;
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Support\FileNames;
use FI\Support\PDF\PDFFactory;
use FI\Support\Statuses\QuoteStatuses;

class ClientCenterPublicQuoteController extends Controller
{
    public function __construct(QuoteRepository $quoteRepository)
    {
        parent::__construct();
        $this->quoteRepository = $quoteRepository;
    }

    public function show($urlKey)
    {
        $quote = $this->quoteRepository->findByUrlKey($urlKey);

        event(new QuoteViewed($quote));

        return view('client_center.quotes.public')
            ->with('quote', $quote)
            ->with('statuses', quoteStatuses::statuses())
            ->with('urlKey', $urlKey);
    }

    public function pdf($urlKey)
    {
        $quote = $this->quoteRepository->findByUrlKey($urlKey);

        event(new QuoteViewed($quote));

        $pdf = PDFFactory::create();

        $pdf->download($quote->html, FileNames::quote($quote));
    }

    public function getPdfContent($urlKey)
    {
        $quote = $this->quoteRepository->findByUrlKey($urlKey);

        $pdf = PDFFactory::create();

        return response()->make($pdf->getOutput($quote->html), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; ' . FileNames::quote($quote),
        ]);
    }

    public function html($urlKey)
    {
        $quote = $this->quoteRepository->findByUrlKey($urlKey);

        return $quote->html;
    }

    public function approve($urlKey)
    {
        $quote = $this->quoteRepository->approve($urlKey);

        event(new QuoteApproved($quote));

        return redirect()->route('clientCenter.public.quote.show', [$urlKey]);
    }

    public function reject($urlKey)
    {
        $quote = $this->quoteRepository->reject($urlKey);

        event(new QuoteRejected($quote));

        return redirect()->route('clientCenter.public.quote.show', [$urlKey]);
    }
}