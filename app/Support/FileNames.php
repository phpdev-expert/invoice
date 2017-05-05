<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Support;

class FileNames
{
    public static function invoice($invoice)
    {
        return trans('fi.invoice') . '_' . $invoice->number . '.pdf';
    }

    public static function quote($quote)
    {
        return trans('fi.quote') . '_' . $quote->number . '.pdf';
    }
}