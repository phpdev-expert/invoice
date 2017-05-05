<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Quotes\Models;

use Carbon\Carbon;
use DB;
use FI\Modules\Currencies\Support\CurrencyConverterFactory;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\Statuses\QuoteStatuses;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    protected $sortable = ['number', 'created_at', 'expires_at', 'clients.name', 'summary', 'quote_amounts.total'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($quote)
        {
            foreach ($quote->items as $item)
            {
                $item->delete();
            }

            foreach ($quote->activities as $activity)
            {
                $activity->delete();
            }

            foreach ($quote->mailQueue as $mailQueue)
            {
                $mailQueue->delete();
            }

            $quote->custom()->delete();
            $quote->amount()->delete();
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
        return $this->hasOne('FI\Modules\Quotes\Models\QuoteAmount');
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
        return $this->hasOne('FI\Modules\CustomFields\Models\QuoteCustom');
    }

    public function group()
    {
        return $this->hasOne('FI\Modules\Groups\Models\Group');
    }

    public function mailQueue()
    {
        return $this->morphMany('FI\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function items()
    {
        return $this->hasMany('FI\Modules\Quotes\Models\QuoteItem')
            ->orderBy('display_order');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
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

    public function getFormattedExpiresAtAttribute($value)
    {
        return DateFormatter::format($this->attributes['expires_at']);
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
        $statuses = QuoteStatuses::statuses();

        return $statuses[$this->attributes['quote_status_id']];
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::quote($this);
    }

    public function getPublicUrlAttribute()
    {
        return route('clientCenter.public.quote.show', [$this->url_key]);
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
        return HTML::quote($this);
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
                    $taxes[$key]          = new \stdClass();
                    $taxes[$key]->name    = $item->taxRate->name;
                    $taxes[$key]->percent = $item->taxRate->formatted_percent;
                    $taxes[$key]->total   = $item->amount->tax_1;
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
                    $taxes[$key]          = new \stdClass();
                    $taxes[$key]->name    = $item->taxRate2->name;
                    $taxes[$key]->percent = $item->taxRate2->formatted_percent;
                    $taxes[$key]->total   = $item->amount->tax_2;
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
        return $query->where('quote_status_id', '=', QuoteStatuses::getStatusId('draft'));
    }

    public function scopeSent($query)
    {
        return $query->where('quote_status_id', '=', QuoteStatuses::getStatusId('sent'));
    }

    public function scopeApproved($query)
    {
        return $query->where('quote_status_id', '=', QuoteStatuses::getStatusId('approved'));
    }

    public function scopeRejected($query)
    {
        return $query->where('quote_status_id', '=', QuoteStatuses::getStatusId('rejected'));
    }

    public function scopeCanceled($query)
    {
        return $query->where('quote_status_id', '=', QuoteStatuses::getStatusId('canceled'));
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
            ->orWhere('quotes.created_at', 'like', '%' . $keywords . '%')
            ->orWhere('expires_at', 'like', '%' . $keywords . '%')
            ->orWhere('summary', 'like', '%' . $keywords . '%')
            ->orWhereIn('client_id', function ($query) use ($keywords)
            {
                $query->select('id')->from('clients')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
            });

        return $query;
    }
}