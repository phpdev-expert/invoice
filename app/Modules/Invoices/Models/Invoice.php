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

use Carbon\Carbon;
use DB;
use FI\Modules\Currencies\Support\CurrencyConverterFactory;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\Statuses\InvoiceStatuses;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use Sortable;

    protected $guarded = ['id'];

    protected $sortable = ['number', 'created_at', 'due_at', 'clients.name', 'summary', 'invoice_amounts.total', 'invoice_amounts.balance'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($invoice)
        {
            foreach ($invoice->items as $item)
            {
                $item->delete();
            }

            foreach ($invoice->payments as $payment)
            {
                $payment->delete();
            }

            foreach ($invoice->activities as $activity)
            {
                $activity->delete();
            }

            foreach ($invoice->mailQueue as $mailQueue)
            {
                $mailQueue->delete();
            }

            foreach ($invoice->recurring as $recurring)
            {
                $recurring->delete();
            }

            $invoice->custom()->delete();
            $invoice->amount()->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activities()
    {
        return $this->morphMany('FI\Modules\Activity\Models\Activity', 'audit');
    }

    public function amount()
    {
        return $this->hasOne('FI\Modules\Invoices\Models\InvoiceAmount');
    }

    public function client()
    {
        return $this->belongsTo('FI\Modules\Clients\Models\Client');
    }

    public function currency()
    {
        return $this->belongsTo('FI\Modules\Currencies\Models\Currency', 'currency_code', 'code');
    }

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\InvoiceCustom');
    }

    public function group()
    {
        return $this->hasOne('FI\Modules\Groups\Models\Group');
    }

    public function items()
    {
        return $this->hasMany('FI\Modules\Invoices\Models\InvoiceItem')
            ->orderBy('display_order');
    }

    public function mailQueue()
    {
        return $this->morphMany('FI\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    public function payments()
    {
        return $this->hasMany('FI\Modules\Payments\Models\Payment');
    }

    public function recurring()
    {
        return $this->hasMany('FI\Modules\Invoices\Models\RecurringInvoice');
    }

    public function transactions()
    {
        return $this->hasMany('FI\Modules\Merchant\Models\InvoiceTransaction');
    }

    public function user()
    {
        return $this->belongsTo('FI\Modules\Users\Models\User');
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

    public function getFormattedDueAtAttribute()
    {
        return DateFormatter::format($this->attributes['due_at']);
    }

    public function getFormattedTermsAttribute()
    {
        return nl2br($this->attributes['terms']);
    }

    public function getFormattedFooterAttribute()
    {
        return nl2br($this->attributes['footer']);
    }

    public function getStatusTextAttribute()
    {
        $statuses = InvoiceStatuses::statuses();

        return $statuses[$this->attributes['invoice_status_id']];
    }

    public function getIsOverdueAttribute()
    {
        // Only invoices in Sent status qualify to be overdue
        if ($this->attributes['due_at'] < date('Y-m-d') and $this->attributes['invoice_status_id'] == InvoiceStatuses::getStatusId('sent'))
            return 1;

        return 0;
    }

    public function getPublicUrlAttribute()
    {
        return route('clientCenter.public.invoice.show', [$this->url_key]);
    }

    public function getIsForeignCurrencyAttribute()
    {
        if ($this->attributes['currency_code'] == config('fi.baseCurrency'))
        {
            return false;
        }

        return true;
    }

    public function getCurrencyCodeAttribute()
    {
        return ($this->attributes['currency_code']) ?: $this->client->currency_code;
    }

    public function getHtmlAttribute()
    {
        return HTML::invoice($this);
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::invoice($this);
    }

    /**
     * Gathers a summary of both invoice and item taxes to be displayed on invoice.
     *
     * @return array
     */
    public function getSummarizedTaxesAttribute()
    {
        $taxes = [];

        foreach ($this->items as $item)
        {
            if ($item->taxRate)
            {
                $key = $item->taxRate->name;

                if (!isset($taxes[$key]))
                {
                    $taxes[$key]              = new \stdClass();
                    $taxes[$key]->name        = $item->taxRate->name;
                    $taxes[$key]->percent     = $item->taxRate->formatted_percent;
                    $taxes[$key]->total       = $item->amount->tax_1;
                    $taxes[$key]->raw_percent = $item->taxRate->percent;
                }
                else
                {
                    $taxes[$key]->total += $item->amount->tax_1;
                }
            }

            if ($item->taxRate2)
            {
                $key = $item->taxRate2->name;

                if (!isset($taxes[$key]))
                {
                    $taxes[$key]              = new \stdClass();
                    $taxes[$key]->name        = $item->taxRate2->name;
                    $taxes[$key]->percent     = $item->taxRate2->formatted_percent;
                    $taxes[$key]->total       = $item->amount->tax_2;
                    $taxes[$key]->raw_percent = $item->taxRate2->percent;
                }
                else
                {
                    $taxes[$key]->total += $item->amount->tax_2;
                }
            }
        }

        foreach ($taxes as $key => $tax)
        {
            $taxes[$key]->total = CurrencyFormatter::format($tax->total, $this->currency);
        }

        return $taxes;
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    public function setExchangeRateAttribute($value)
    {
        if ($this->attributes['currency_code'] == config('fi.baseCurrency'))
        {
            $this->attributes['exchange_rate'] = 1;
        }

        elseif (config('fi.exchangeRateMode') == 'automatic' and !$value)
        {
            $currencyConverter = CurrencyConverterFactory::create();

            $this->attributes['exchange_rate'] = $currencyConverter->convert(config('fi.baseCurrency'), $this->attributes['currency_code']);
        }

        else
        {
            $this->attributes['exchange_rate'] = $value;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeDraft($query)
    {
        return $query->where('invoice_status_id', '=', InvoiceStatuses::getStatusId('draft'));
    }

    public function scopeSent($query)
    {
        return $query->where('invoice_status_id', '=', InvoiceStatuses::getStatusId('sent'));
    }

    public function scopePaid($query)
    {
        return $query->where('invoice_status_id', '=', InvoiceStatuses::getStatusId('paid'));
    }

    public function scopeCanceled($query)
    {
        return $query->where('invoice_status_id', '=', InvoiceStatuses::getStatusId('canceled'));
    }

    public function scopeStatusIn($query, $statuses)
    {
        $statusCodes = [];

        foreach ($statuses as $status)
        {
            $statusCodes[] = InvoiceStatuses::getStatusId($status);
        }

        return $query->whereIn('invoice_status_id', $statusCodes);
    }

    public function scopeOverdue($query)
    {
        // Only invoices in Sent status qualify to be overdue
        return $query
            ->where('invoice_status_id', '=', InvoiceStatuses::getStatusId('sent'))
            ->where('due_at', '<', date('Y-m-d'));
    }

    public function scopeDueOnDate($query, $date)
    {
        return $query
            ->where('invoice_status_id', '=', InvoiceStatuses::getStatusId('sent'))
            ->where('due_at', $date);
    }

    public function scopeYearToDate($query)
    {
        return $query->where('created_at', '>=', date('Y') . '-01-01')
            ->where('created_at', '<=', date('Y') . '-12-31');
    }

    public function scopeThisQuarter($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->firstOfQuarter())
            ->where('created_at', '<=', Carbon::now()->lastOfQuarter());
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('created_at', '>=', DateFormatter::unformat($fromDate))
            ->where('created_at', '<=', DateFormatter::unformat($toDate));
    }

    public function scopeKeywords($query, $keywords)
    {
        $keywords = strtolower($keywords);

        $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
            ->orWhere('invoices.created_at', 'like', '%' . $keywords . '%')
            ->orWhere('due_at', 'like', '%' . $keywords . '%')
            ->orWhere('summary', 'like', '%' . $keywords . '%')
            ->orWhereIn('client_id', function ($query) use ($keywords)
            {
                $query->select('id')->from('clients')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
            });

        return $query;
    }
}