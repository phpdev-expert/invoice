<?php

namespace FI\Composers;

use FI\Modules\Currencies\Repositories\CurrencyRepository;
use FI\Modules\Invoices\Support\InvoiceTemplates;
use FI\Modules\Quotes\Support\QuoteTemplates;
use FI\Support\Languages;

class ClientFormComposer
{
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function compose($view)
    {
        $view->with('currencies', $this->currencyRepository->lists())
            ->with('invoiceTemplates', InvoiceTemplates::lists())
            ->with('quoteTemplates', QuoteTemplates::lists())
            ->with('languages', Languages::listLanguages());
    }
}