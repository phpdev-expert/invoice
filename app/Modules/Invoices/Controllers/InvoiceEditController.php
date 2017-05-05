<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Currencies\Repositories\CurrencyRepository;
use FI\Modules\CustomFields\Repositories\CustomFieldRepository;
use FI\Modules\Invoices\Repositories\InvoiceRepository;
use FI\Modules\Invoices\Support\InvoiceTemplates;
use FI\Modules\Invoices\Validators\InvoiceValidator;
use FI\Modules\TaxRates\Repositories\TaxRateRepository;
use FI\Support\BackPath;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Validators\ItemValidator;

class InvoiceEditController extends Controller
{
    public function __construct(
        CurrencyRepository $currencyRepository,
        CustomFieldRepository $customFieldRepository,
        InvoiceRepository $invoiceRepository,
        InvoiceValidator $invoiceValidator,
        ItemValidator $itemValidator,
        TaxRateRepository $taxRateRepository
    )
    {
        parent::__construct();

        $this->currencyRepository    = $currencyRepository;
        $this->customFieldRepository = $customFieldRepository;
        $this->invoiceRepository     = $invoiceRepository;
        $this->invoiceValidator      = $invoiceValidator;
        $this->itemValidator         = $itemValidator;
        $this->taxRateRepository     = $taxRateRepository;
    }

    public function edit($id)
    {
        $invoice = $this->invoiceRepository->with(['items.amount.item.invoice.currency'])->find($id);

        return view('invoices.edit')
            ->with('invoice', $invoice)
            ->with('statuses', InvoiceStatuses::lists())
            ->with('currencies', $this->currencyRepository->lists())
            ->with('taxRates', $this->taxRateRepository->lists())
            ->with('customFields', $this->customFieldRepository->getByTable('invoices'))
            ->with('backPath', BackPath::getBackPath())
            ->with('templates', InvoiceTemplates::lists())
            ->with('itemCount', count($invoice->items));
    }

    public function update($id)
    {
        $validator = $this->invoiceValidator->getUpdateValidator(request()->all());

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors'  => $validator->messages()->toArray()
            ], 400);
        }

        foreach (json_decode(request('items')) as $item)
        {
            $itemValidator = $this->itemValidator->getValidator($item);

            if ($itemValidator->fails())
            {
                return response()->json([
                    'success' => false,
                    'errors'  => $itemValidator->messages()->toArray()
                ], 400);
            }
        }

        $this->invoiceRepository->update(request()->all(), $id);

        return response()->json(['success' => true], 200);
    }

    public function refreshEdit($id)
    {
        $invoice = $this->invoiceRepository->with(['items.amount.item.invoice.currency'])->find($id);

        return view('invoices._edit')
            ->with('invoice', $invoice)
            ->with('statuses', InvoiceStatuses::lists())
            ->with('currencies', $this->currencyRepository->lists())
            ->with('taxRates', $this->taxRateRepository->lists())
            ->with('customFields', $this->customFieldRepository->getByTable('invoices'))
            ->with('backPath', BackPath::getBackPath())
            ->with('templates', InvoiceTemplates::lists())
            ->with('itemCount', count($invoice->items));
    }

    public function refreshTotals()
    {
        return view('invoices._edit_totals')
            ->with('invoice', $this->invoiceRepository->with(['items.amount.item.invoice.currency'])->find(request('id')));
    }

    public function refreshTo()
    {
        return view('invoices._edit_to')
            ->with('invoice', $this->invoiceRepository->find(request('id')));
    }

    public function refreshFrom()
    {
        return view('invoices._edit_from')
            ->with('invoice', $this->invoiceRepository->find(request('id')));
    }

    public function updateClient()
    {
        $this->invoiceRepository->updateRaw(['client_id' => request('client_id')], request('id'));
    }

    public function updateUser()
    {
        $this->invoiceRepository->updateRaw(['user_id' => request('user_id')], request('id'));
    }
}