<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTransaction extends Model
{
    protected $table   = 'invoice_transactions';

    protected $guarded = ['id'];
}