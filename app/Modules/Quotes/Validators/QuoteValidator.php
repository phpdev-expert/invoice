<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Validators;

use Validator;

class QuoteValidator
{
    public function getValidator($input)
    {
        return Validator::make($input, [
                'client_name' => 'required',
                'user_id'     => 'required',
            ]
        );
    }

    public function getUpdateValidator($input)
    {
        return Validator::make($input, [
                'summary'         => 'max:100',
                'created_at'      => 'required',
                'expires_at'      => 'required',
                'number'          => 'required',
                'quote_status_id' => 'required',
                'exchange_rate'   => 'required|numeric'
            ]
        );
    }

    public function getToInvoiceValidator($input)
    {
        return Validator::make($input, [
                'client_id'  => 'required',
                'created_at' => 'required',
                'group_id'   => 'required'
            ]
        );
    }

    public function getRawValidator($input)
    {
        return Validator::make($input, [
                'summary'         => 'max:100',
                'created_at'      => 'required|date',
                'user_id'         => 'required|integer',
                'client_id'       => 'required|integer',
                'group_id'        => 'required|integer',
                'quote_status_id' => 'required|integer',
                'expires_at'      => 'required|date',
                'number'          => 'required'
            ]
        );
    }
}