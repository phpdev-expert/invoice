<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Validators;

use FI\Support\NumberFormatter;
use Validator;

class ItemValidator
{
    public function getValidator($input)
    {
        $input = (array)$input;

        $input['item_quantity'] = NumberFormatter::unformat($input['item_quantity']);
        $input['item_price']    = NumberFormatter::unformat($input['item_price']);

        $validator = Validator::make($input, [
                'item_quantity' => 'numeric',
                'item_price'    => 'numeric'
            ]
        );

        $validator->sometimes('item_name', 'required', function ($input)
        {
            if ($input['item_quantity'] or $input['item_price'])
            {
                return true;
            }
        });

        $validator->sometimes('item_price', 'required', function ($input)
        {
            if ($input['item_quantity'] or $input['item_name'])
            {
                return true;
            }
        });

        $validator->sometimes('item_quantity', 'required', function ($input)
        {
            if ($input['item_name'] or $input['item_price'])
            {
                return true;
            }
        });

        return $validator;
    }

    public function getApiInvoiceValidator($input)
    {
        return Validator::make($input, [
            'invoice_id'    => 'required',
            'item_name'     => 'required',
            'item_quantity' => 'required'
        ]);
    }

    public function getApiQuoteValidator($input)
    {
        return Validator::make($input, [
            'quote_id'      => 'required',
            'item_name'     => 'required',
            'item_quantity' => 'required'
        ]);
    }
}