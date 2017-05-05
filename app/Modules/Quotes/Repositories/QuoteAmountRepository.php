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
use FI\Modules\Quotes\Models\QuoteAmount;

class QuoteAmountRepository
{
    public function findByQuoteId($quoteId)
    {
        return QuoteAmount::where('quote_id', '=', $quoteId)->first();
    }

    public function create($quoteId)
    {
        return QuoteAmount::create([
                'quote_id' => $quoteId,
                'subtotal' => 0,
                'tax'      => 0,
                'total'    => 0
            ]
        );
    }

    public function update($input, $quoteId)
    {
        $quoteAmount = QuoteAmount::where('quote_id', $quoteId)->first();

        if ($quoteAmount)
        {
            $quoteAmount->fill($input);

            $quoteAmount->save();
        }

        return $quoteAmount;
    }
}