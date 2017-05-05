<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Merchant\Support;

class MerchantProperties
{
    public static function setProperties($merchants)
    {
        foreach (array_keys($merchants) as $merchant)
        {
            $merchantLib = '\\FI\\Modules\\Merchant\\Support\\Drivers\\' . $merchant;

            $merchants[$merchant]['isRedirect'] = $merchantLib::isRedirect();

            if ($merchant == 'PayPalExpress')
            {
                $merchants[$merchant]['name'] = 'PayPal';
            }
            else
            {
                $merchants[$merchant]['name'] = $merchant;
            }
        }

        return $merchants;
    }
}