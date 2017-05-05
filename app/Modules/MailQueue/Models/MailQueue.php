<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\MailQueue\Models;

use Illuminate\Database\Eloquent\Model;

class MailQueue extends Model
{
    protected $table = 'mail_queue';

    protected $guarded = [];

    public function mailable()
    {
        return $this->morphTo();
    }
}