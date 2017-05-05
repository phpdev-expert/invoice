<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Settings\Models;

use FI\Events\TemplateDefaultChanged;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($setting)
        {
            if ($setting->setting_key == 'invoiceTemplate' or $setting->setting_key == 'quoteTemplate')
            {
                $original = $setting->getOriginal();

                if (isset($original['setting_value']) and $original['setting_value'] !== $setting->setting_value)
                {
                    event(new TemplateDefaultChanged($setting->setting_key, $original['setting_value'], $setting->setting_value));
                }
            }
        });
    }
}