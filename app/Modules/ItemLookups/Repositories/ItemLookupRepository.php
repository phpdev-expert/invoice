<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ItemLookups\Repositories;

use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Support\NumberFormatter;

class ItemLookupRepository
{
    public function find($id)
    {
        return ItemLookup::find($id);
    }

    public function paginate()
    {
        return ItemLookup::sortable(['name' => 'asc'])->paginate(config('fi.resultsPerPage'));
    }

    public function getJsonLookup($query)
    {
        $items = ItemLookup::orderBy('name')->where('name', 'like', '%' . $query . '%')->get();

        $return = [];

        foreach ($items as $item)
        {
            $return[] = [
                'item_name'        => $item->name,
                'item_description' => $item->description,
                'item_price'       => NumberFormatter::format($item->price)
            ];
        }

        return json_encode($return);
    }

    public function create($input)
    {
        return ItemLookup::create($input);
    }

    public function update($input, $id)
    {
        $itemLookup = ItemLookup::find($id);

        $itemLookup->fill($input);

        $itemLookup->save();

        return $itemLookup;
    }

    public function delete($id)
    {
        ItemLookup::destroy($id);
    }
}