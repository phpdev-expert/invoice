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

use FI\Modules\ItemLookups\Repositories\ItemLookupRepository;
use FI\Modules\Quotes\Models\QuoteItem;
use FI\Events\QuoteModified;
use FI\Support\NumberFormatter;

class QuoteItemRepository
{
    public function __construct(ItemLookupRepository $itemLookupRepository)
    {
        $this->itemLookupRepository = $itemLookupRepository;
    }

    public function find($id)
    {
        return QuoteItem::find($id);
    }

    public function findByQuoteId($quoteId)
    {
        return QuoteItem::orderBy('display_order')->where('quote_id', '=', $quoteId)->get();
    }

    public function create($input)
    {
        return QuoteItem::create($input);
    }

    public function update($input, $id)
    {
        $quoteItem = QuoteItem::find($id);

        $quoteItem->fill($input);

        $quoteItem->save();

        return $quoteItem;
    }

    public function saveItems($items, $applyExchangeRate = false, $exchangeRate = 1)
    {
        foreach ($items as $item)
        {
            if ($item['item_name'])
            {
                $itemDescription = (isset($item['item_description'])) ? $item['item_description'] : '';
                $itemTaxRateId   = (isset($item['item_tax_rate_id'])) ? $item['item_tax_rate_id'] : 0;
                $itemTaxRate2Id  = (isset($item['item_tax_rate_2_id'])) ? $item['item_tax_rate_2_id'] : 0;
                $itemOrder       = (isset($item['item_order'])) ? $item['item_order'] : 1;

                $itemRecord = [
                    'quote_id'      => $item['quote_id'],
                    'name'          => $item['item_name'],
                    'description'   => $itemDescription,
                    'quantity'      => NumberFormatter::unformat($item['item_quantity']),
                    'price'         => (($applyExchangeRate) ? (NumberFormatter::unformat($item['item_price']) * $exchangeRate) : NumberFormatter::unformat($item['item_price'])),
                    'tax_rate_id'   => $itemTaxRateId,
                    'tax_rate_2_id' => $itemTaxRate2Id,
                    'display_order' => $itemOrder
                ];

                if (!isset($item['item_id']) or (!$item['item_id']))
                {
                    QuoteItem::create($itemRecord);
                }
                else
                {
                    $quoteItem = QuoteItem::find($item['item_id']);

                    $quoteItem->fill($itemRecord);

                    $quoteItem->save();
                }

                if (isset($item['save_item_as_lookup']))
                {
                    $itemLookupRecord = [
                        'name'        => $item['item_name'],
                        'description' => $item['item_description'],
                        'price'       => NumberFormatter::unformat($item['item_price'])
                    ];

                    $this->itemLookupRepository->create($itemLookupRecord);
                }
            }
        }
    }

    public function delete($id)
    {
        $quoteItem = QuoteItem::find($id);

        $quote = $quoteItem->quote;

        $quoteItem->delete();

        event(new QuoteModified($quote));
    }
}