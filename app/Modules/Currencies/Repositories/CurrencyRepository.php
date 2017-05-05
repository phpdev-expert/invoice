<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Currencies\Repositories;

use FI\Modules\Clients\Models\Client;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\Invoices\Models\Invoice;
use FI\Modules\Quotes\Models\Quote;

class CurrencyRepository
{
    public function find($id)
    {
        return Currency::find($id);
    }

    public function findByCode($code)
    {
        return Currency::where('code', $code)->first();
    }

    public function paginate()
    {
        return Currency::sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function lists()
    {
        return Currency::orderBy('name')->lists('name', 'code')->all();
    }

    public function create($input)
    {
        return Currency::create($input);
    }

    public function update($input, $id)
    {
        $currency = Currency::find($id);

        $currency->fill($input);

        $currency->save();

        return $currency;
    }

    public function delete($id)
    {
        $currency = Currency::find($id);

        if ($currency->code == config('fi.baseCurrency'))
        {
            return trans('fi.cannot_delete_base_currency');
        }

        if (Client::where('currency_code', '=', $currency->code)->count())
        {
            return trans('fi.cannot_delete_client_currency');
        }

        if (Quote::where('currency_code', '=', $currency->code)->count())
        {
            return trans('fi.cannot_delete_quote_currency');
        }

        if (Invoice::where('currency_code', '=', $currency->code)->count())
        {
            return trans('fi.cannot_delete_invoice_currency');
        }

        Currency::destroy($id);

        return trans('fi.record_successfully_deleted');
    }

    public function currencyInUse($id)
    {
        $currency = Currency::find($id);

        if ($currency->code == config('fi.baseCurrency'))
        {
            return true;
        }

        if (Client::where('currency_code', '=', $currency->code)->count())
        {
            return true;
        }

        if (Quote::where('currency_code', '=', $currency->code)->count())
        {
            return true;
        }

        if (Invoice::where('currency_code', '=', $currency->code)->count())
        {
            return true;
        }

        return false;
    }
}