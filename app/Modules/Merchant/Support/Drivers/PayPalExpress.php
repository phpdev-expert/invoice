<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Merchant\Support\Drivers;

use Omnipay\Omnipay;

class PayPalExpress
{
    public static function createGateway()
    {
        $gateway = Omnipay::create('PayPal_Express');

        $merchant = json_decode(config()->get('fi.merchant'));

        $gateway->setUsername($merchant->PayPalExpress->username);
        $gateway->setPassword($merchant->PayPalExpress->password);
        $gateway->setSignature($merchant->PayPalExpress->signature);

        if (app()->environment('fidev'))
        {
            $gateway->setTestMode(true);
        }

        return $gateway;
    }

    public static function setPurchaseParameters($purchaseParameters, $params)
    {
        $purchaseParameters['returnUrl'] = route('merchant.invoice.return', [$params['urlKey'], 'merchant' => 'PayPalExpress']);
        $purchaseParameters['cancelUrl'] = route('merchant.invoice.cancel', [$params['urlKey']]);

        return $purchaseParameters;
    }

    public static function isRedirect()
    {
        return true;
    }

    public static function isNotify()
    {
        return false;
    }
}