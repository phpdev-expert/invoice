<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Groups\Models;

use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded  = ['id'];

    protected $sortable = ['name', 'prefix', 'next_id', 'left_pad', 'prefix_year', 'prefix_month'];
}