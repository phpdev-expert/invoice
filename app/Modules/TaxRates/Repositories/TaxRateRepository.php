<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\TaxRates\Repositories;

use FI\Modules\Invoices\Models\InvoiceItem;
use FI\Modules\Quotes\Models\QuoteItem;
use FI\Modules\TaxRates\Models\TaxRate;

class TaxRateRepository
{
    public function find($id)
    {
        return TaxRate::find($id);
    }

    public function paginate()
    {
        return TaxRate::sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function findIdByName($name)
    {
        if ($taxRate = TaxRate::where('name', $name)->first())
        {
            return $taxRate->id;
        }

        return null;
    }

    public function lists()
    {
        return ['0' => trans('fi.none')] + TaxRate::lists('name', 'id')->all();
    }

    public function create($input)
    {
        return TaxRate::create($input);
    }

    public function update($input, $id)
    {
        $taxRate = TaxRate::find($id);

        $taxRate->fill($input);

        $taxRate->save();

        return $taxRate;
    }

    public function delete($id)
    {
        if ($this->taxRateInUse($id))
        {
            return trans('fi.cannot_delete_tax_rate');
        }

        TaxRate::destroy($id);

        return trans('fi.record_successfully_deleted');
    }

    public function taxRateInUse($id)
    {
        if (InvoiceItem::where('tax_rate_id', $id)->orWhere('tax_rate_2_id', $id)->count())
        {
            return true;
        }

        if (QuoteItem::where('tax_rate_id', $id)->orWhere('tax_rate_2_id', $id)->count())
        {
            return true;
        }

        if (config('fi.itemTaxRate') == $id or config('fi.itemTax2Rate') == $id)
        {
            return true;
        }

        return false;
    }
}