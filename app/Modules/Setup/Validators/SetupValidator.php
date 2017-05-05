<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Setup\Validators;

use Validator;

class SetupValidator
{
    public function getLicenseValidator($input)
    {
        return Validator::make($input, [
                'accept' => 'accepted'
            ]
        );
    }

    public function getUserValidator($input)
    {
        return Validator::make($input, [
                'name'     => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed'
            ]
        );
    }
}