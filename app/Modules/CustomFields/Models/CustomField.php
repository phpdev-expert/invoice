<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\CustomFields\Models;

use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded  = ['id'];

    protected $sortable = ['table_name', 'column_name', 'field_label', 'field_type'];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForTable($query, $table)
    {
        return $query->where('table_name', '=', $table);
    }
}