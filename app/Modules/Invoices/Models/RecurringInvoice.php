<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Invoices\Models;

use DB;
use FI\Support\DateFormatter;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class RecurringInvoice extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded  = ['id'];

    protected $sortable = ['invoices.number', 'invoices.summary', 'clients.name', 'generate_at', 'stop_at', 'recurring_frequency'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function invoice()
    {
        return $this->belongsTo('FI\Modules\Invoices\Models\Invoice');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedCreatedAtAttribute()
    {
        return DateFormatter::format($this->attributes['created_at']);
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return DateFormatter::format($this->attributes['updated_at']);
    }

    public function getFormattedGenerateAtAttribute()
    {
        return ($this->attributes['generate_at'] !== '0000-00-00') ? DateFormatter::format($this->attributes['generate_at']) : '';
    }

    public function getFormattedStopAtAttribute()
    {
        return ($this->attributes['stop_at'] !== '0000-00-00') ? DateFormatter::format($this->attributes['stop_at']) : '';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeRecurNow($query)
    {
        $query->where('generate_at', '<>', '0000-00-00');
        $query->where('generate_at', '<=', date('Y-m-d'));
        $query->where(function ($q)
        {
            $q->where('stop_at', '0000-00-00');
            $q->orWhere('generate_at', '<=', DB::raw('stop_at'));
        });
    }

    public function scopeKeywords($query, $keywords)
    {
        $keywords = strtolower($keywords);

        $query->where('generate_at', 'like', '%' . $keywords . '%')
            ->orWhereIn('invoice_id', function ($query) use ($keywords)
            {
                $query->select('id')->from('invoices')->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                    ->orWhere('summary', 'like', '%' . $keywords . '%')
                    ->orWhereIn('client_id', function ($query) use ($keywords)
                    {
                        $query->select('id')->from('clients')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
                    });
            });

        return $query;

//        $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
//            ->orWhere('invoices.created_at', 'like', '%' . $keywords . '%')
//            ->orWhere('due_at', 'like', '%' . $keywords . '%')
//            ->orWhere('summary', 'like', '%' . $keywords . '%')
//            ->orWhereIn('client_id', function ($query) use ($keywords)
//            {
//                $query->select('id')->from('clients')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
//            });
//
//        return $query;
    }
}