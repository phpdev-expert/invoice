<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Settings\Repositories;

use FI\Modules\Settings\Models\Setting;
use Illuminate\Database\QueryException;
use PDOException;

class SettingRepository
{
    public function setAll()
    {
        try
        {
            $settings = Setting::all();

            foreach ($settings as $setting)
            {
                config(['fi.' . $setting->setting_key => $setting->setting_value]);
            }

            return true;

        }
        catch (QueryException $e)
        {
            return false;
        }
        catch (PDOException $e)
        {
            return false;
        }
    }

    public function get($key)
    {
        return Setting::where('setting_key', $key)->first()->setting_value;
    }

    public function save($key, $value)
    {
        if ($setting = Setting::where('setting_key', $key)->first())
        {
            $setting->setting_value = $value;
            $setting->save();
        }
        else
        {
            Setting::create(['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public function delete($key)
    {
        Setting::where('setting_key', $key)->delete();
    }
}