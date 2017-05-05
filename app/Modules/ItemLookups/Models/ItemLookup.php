<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\ItemLookups\Models;

use FI\Support\CurrencyFormatter;
use FI\Support\NumberFormatter;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class ItemLookup extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded  = ['id'];

    protected $sortable = ['name', 'description', 'price'];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedPriceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['price']);
    }

    public function getFormattedNumericPriceAttribute()
    {
        return NumberFormatter::format($this->attributes['price']);
    }
}