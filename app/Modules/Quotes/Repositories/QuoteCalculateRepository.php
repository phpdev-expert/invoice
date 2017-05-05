<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Repositories;

use DB;
use FI\Modules\Quotes\Models\Quote;
use FI\Modules\Quotes\Models\QuoteAmount;
use FI\Modules\Quotes\Models\QuoteItem;
use FI\Modules\Quotes\Models\QuoteItemAmount;
use FI\Support\Calculators\QuoteCalculator;

class QuoteCalculateRepository
{
    public function calculate($quoteId)
    {
        // Need to use a few DB::raw()'s because it seems Laravel likes to prefix aliases incorrectly
        $quoteItems = QuoteItem::select('quote_items.*',
            'tax_rates_1.percent AS tax_rate_1_percent',
            'tax_rates_2.percent AS tax_rate_2_percent',
            'tax_rates_2.is_compound AS tax_rate_2_is_compound')
            ->leftJoin('tax_rates AS tax_rates_1', 'quote_items.tax_rate_id', '=', 'tax_rates_1.id')
            ->leftJoin('tax_rates AS tax_rates_2', 'quote_items.tax_rate_2_id', '=', 'tax_rates_2.id')
            ->where('quote_id', $quoteId)
            ->get();

        $calculator = new QuoteCalculator;
        $calculator->setId($quoteId);

        foreach ($quoteItems as $quoteItem)
        {
            $taxRatePercent     = ($quoteItem->tax_rate_id) ? $quoteItem->tax_rate_1_percent : 0;
            $taxRate2Percent    = ($quoteItem->tax_rate_2_id) ? $quoteItem->tax_rate_2_percent : 0;
            $taxRate2IsCompound = ($quoteItem->tax_rate_2_is_compound) ? 1 : 0;

            $calculator->addItem($quoteItem->id, $quoteItem->quantity, $quoteItem->price, $taxRatePercent, $taxRate2Percent, $taxRate2IsCompound);
        }

        $calculator->calculate();

        // Get the calculated values
        $calculatedItemAmounts = $calculator->getCalculatedItemAmounts();
        $calculatedAmount      = $calculator->getCalculatedAmount();

        // Update the item amount records
        foreach ($calculatedItemAmounts as $calculatedItemAmount)
        {
            $quoteItemAmount = QuoteItemAmount::firstOrNew(['item_id' => $calculatedItemAmount['item_id']]);
            $quoteItemAmount->fill($calculatedItemAmount);
            $quoteItemAmount->save();
        }

        // Update the overall quote amount record
        $quoteAmount = QuoteAmount::firstOrNew(['quote_id' => $quoteId]);
        $quoteAmount->fill($calculatedAmount);
        $quoteAmount->save();
    }

    public function calculateAll()
    {
        $quoteIds = Quote::select('id')->get();

        foreach ($quoteIds as $quoteId)
        {
            $this->calculate($quoteId->id);
        }
    }
}