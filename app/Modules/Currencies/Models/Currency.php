<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Currencies\Models;

use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use Sortable;

    protected $table    = 'currencies';

    protected $sortable = ['code', 'name', 'symbol', 'placement', 'decimal', 'thousands'];

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    public function getFormattedPlacementAttribute()
    {
        return ($this->placement == 'before') ? trans('fi.before_amount') : trans('fi.after_amount');
    }
}