<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Payments\Models;

use DB;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\NumberFormatter;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    protected $sortable = ['paid_at', 'invoices.created_at', 'invoices.number', 'invoices.summary', 'clients.name', 'amount', 'payment_methods.name', 'note'];

    public static function boot()
    {
        static::deleting(function ($payment)
        {
            foreach ($payment->mailQueue as $mailQueue)
            {
                $mailQueue->delete();
            }

            $payment->custom()->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\PaymentCustom');
    }

    public function invoice()
    {
        return $this->belongsTo('FI\Modules\Invoices\Models\Invoice');
    }

    public function mailQueue()
    {
        return $this->morphMany('FI\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('FI\Modules\PaymentMethods\Models\PaymentMethod');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedPaidAtAttribute()
    {
        return DateFormatter::format($this->attributes['paid_at']);
    }

    public function getFormattedAmountAttribute()
    {
        return CurrencyFormatter::format($this->attributes['amount'], $this->invoice->currency);
    }

    public function getFormattedNumericAmountAttribute()
    {
        return NumberFormatter::format($this->attributes['amount']);
    }

    public function getFormattedNoteAttribute()
    {
        return nl2br($this->attributes['note']);
    }

    public function getUserAttribute()
    {
        return $this->invoice->user;
    }

    public function getHtmlAttribute()
    {
        return HTML::invoice($this->invoice);
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::invoice($this->invoice);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->where('paid_at', '>=', $from)->where('paid_at', '<=', $to);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('paid_at', '>=', $year . '-01-01')
            ->where('paid_at', '<=', $year . '-12-31');
    }

    public function scopeKeywords($query, $keywords)
    {
        $keywords = strtolower($keywords);

        $query->where('payments.created_at', 'like', '%' . $keywords . '%')
            ->orWhereIn('invoice_id', function ($query) use ($keywords)
            {
                $query->select('id')->from('invoices')->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                    ->orWhere('summary', 'like', '%' . $keywords . '%')
                    ->orWhereIn('client_id', function ($query) use ($keywords)
                    {
                        $query->select('id')->from('clients')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
                    });
            })
            ->orWhereIn('payment_method_id', function ($query) use ($keywords)
            {
                $query->select('id')->from('payment_methods')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
            });

        return $query;
    }
}