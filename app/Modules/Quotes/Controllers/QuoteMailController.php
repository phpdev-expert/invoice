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

use FI\Events\QuoteEmailed;
use FI\Modules\MailQueue\Repositories\MailQueueRepository;
use FI\Modules\Quotes\Repositories\QuoteRepository;
use FI\Http\Controllers\Controller;
use FI\Support\Parser;
use FI\Validators\SendEmailValidator;

class QuoteMailController extends Controller
{
    public function __construct(
        MailQueueRepository $mailQueueRepository,
        QuoteRepository $quoteRepository,
        SendEmailValidator $sendEmailValidator)
    {
        parent::__construct();

        $this->mailQueueRepository = $mailQueueRepository;
        $this->quoteRepository     = $quoteRepository;
        $this->sendEmailValidator  = $sendEmailValidator;
    }

    public function create()
    {
        $quote = $this->quoteRepository->find(request('quote_id'));

        return view('quotes._modal_mail')
            ->with('quoteId', $quote->id)
            ->with('redirectTo', request('redirectTo'))
            ->with('to', $quote->client->email)
            ->with('cc', config('fi.mailDefaultCc'))
            ->with('bcc', config('fi.mailDefaultBcc'))
            ->with('subject', trans('fi.quote') . ' #' . $quote->number)
            ->with('body', Parser::parse($quote, config('fi.quoteEmailBody')));
    }

    public function store()
    {
        $validator = $this->sendEmailValidator->getValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        $quote = $this->quoteRepository->find(request('quote_id'));

        $this->mailQueueRepository->create($quote, request()->except('quote_id'));

        event(new QuoteEmailed($quote));
    }
}