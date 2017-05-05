<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Reports\Validators;

use Validator;

class ClientStatementReportValidator
{
    public function getValidator($input)
    {
        return Validator::make($input, [
                'from_date'   => 'required',
                'to_date'     => 'required',
                'client_name' => 'required'
            ]
        );
    }
}