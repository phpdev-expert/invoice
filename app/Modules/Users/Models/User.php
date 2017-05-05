<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Users\Models;

use FI\Traits\Sortable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are guarded from mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden   = ['password', 'remember_token', 'api_public_key', 'api_secret_key'];

    protected $sortable = ['name', 'email'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($user)
        {
            $user->address = strip_tags($user->address);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo('FI\Modules\Clients\Models\Client');
    }

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\UserCustom');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedAddressAttribute()
    {
        return nl2br(formatAddress($this));
    }

    public function getIsAdminAttribute()
    {
        return ($this->client_id) ? false : true;
    }
}