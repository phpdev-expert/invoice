<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Clients\Models;

use DB;
use FI\Support\CurrencyFormatter;
use FI\Traits\Sortable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class Client extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Sortable;

    protected $guarded = ['id', 'password', 'allow_login'];

    protected $hidden = ['password', 'remember_token'];

    protected $sortable = ['unique_name', 'email', 'phone', 'balance', 'active'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($client)
        {
            $client->url_key = str_random(32);

            if (!$client->currency_code)
            {
                $client->currency_code = config('fi.baseCurrency');
            }

            if (!$client->invoice_template)
            {
                $client->invoice_template = config('fi.invoiceTemplate');
            }

            if (!$client->quote_template)
            {
                $client->quote_template = config('fi.quoteTemplate');
            }

            if (!$client->language)
            {
                $client->language = config('fi.language');
            }
        });

        static::saving(function ($client)
        {
            $client->name    = strip_tags($client->name);
            $client->address = strip_tags($client->address);

            if (!$client->unique_name)
            {
                $client->unique_name = $client->name;
            }
        });

        static::deleting(function ($client)
        {
            foreach ($client->quotes as $quote)
            {
                $quote->delete();
            }

            foreach ($client->invoices as $invoice)
            {
                $invoice->delete();
            }

            $client->user()->delete();
            $client->custom()->delete();
        });
    }

    /**
     * Multiple methods will use this.
     *
     * @return Client
     */
    protected function getQuery()
    {
        return Client::select('clients.*',
            DB::raw('(' . $this->getBalanceSql() . ') as balance'),
            DB::raw('(' . $this->getPaidSql() . ') AS paid'),
            DB::raw('(' . $this->getTotalSql() . ') AS total')
        );
    }

    /**
     * Constructs a subquery to return the total balance amount.
     *
     * @return DB
     */
    protected function getBalanceSql()
    {
        return DB::table('invoice_amounts')->select(DB::raw('sum(balance)'))->whereIn('invoice_id', function ($q)
        {
            $q->select('id')->from('invoices')->where('invoices.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'));
        })->toSql();
    }

    /**
     * Constructs a subquery to return the total paid amount.
     *
     * @return DB
     */
    protected function getPaidSql()
    {
        return DB::table('invoice_amounts')->select(DB::raw('sum(paid)'))->whereIn('invoice_id', function ($q)
        {
            $q->select('id')->from('invoices')->where('invoices.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'));
        })->toSql();
    }

    /**
     * Constructs a subquery to return the total billed amount.
     *
     * @return DB
     */
    protected function getTotalSql()
    {
        return DB::table('invoice_amounts')->select(DB::raw('sum(total)'))->whereIn('invoice_id', function ($q)
        {
            $q->select('id')->from('invoices')->where('invoices.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'));
        })->toSql();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function currency()
    {
        return $this->belongsTo('FI\Modules\Currencies\Models\Currency', 'currency_code', 'code');
    }

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\ClientCustom');
    }

    public function invoices()
    {
        return $this->hasMany('FI\Modules\Invoices\Models\Invoice');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    public function quotes()
    {
        return $this->hasMany('FI\Modules\Quotes\Models\Quote');
    }

    public function user()
    {
        return $this->hasOne('FI\Modules\Users\Models\User');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedBalanceAttribute()
    {
        return CurrencyFormatter::format($this->balance, $this->currency);
    }

    public function getFormattedPaidAttribute()
    {
        return CurrencyFormatter::format($this->paid, $this->currency);
    }

    public function getFormattedTotalAttribute()
    {
        return CurrencyFormatter::format($this->total, $this->currency);
    }

    public function getFormattedAddressAttribute()
    {
        return nl2br(formatAddress($this));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeKeywords($query, $keywords)
    {
        $keywords = explode(' ', $keywords);

        foreach ($keywords as $keyword)
        {
            if ($keyword)
            {
                $keyword = strtolower($keyword);

                $query->where(\DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name),LOWER(email),phone,fax,mobile)"), 'LIKE', "%$keyword%");
            }
        }

        return $query;
    }
}