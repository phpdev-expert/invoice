<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Payments\Validators;

use FI\Support\NumberFormatter;
use Validator;

class PaymentValidator
{
    public function getValidator($input)
    {
        $input['amount'] = (isset($input['amount'])) ? NumberFormatter::unformat($input['amount']) : null;

        return Validator::make($input, [
                'invoice_id'        => 'required',
                'amount'            => 'required|numeric',
                'payment_method_id' => 'required'
            ]
        );
    }
}