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

class Stripe
{
    public static function createGateway()
    {
        $gateway = Omnipay::create('Stripe');

        $merchant = json_decode(config()->get('fi.merchant'));

        $gateway->setApiKey($merchant->Stripe->secretKey);

        if (app()->environment('fidev'))
        {
            $gateway->setTestMode(true);
        }

        return $gateway;
    }

    public static function setPurchaseParameters($purchaseParameters, $params)
    {
        $purchaseParameters['token'] = $params['post']['stripeToken'];

        return $purchaseParameters;
    }

    public static function isRedirect()
    {
        return false;
    }

    public static function isNotify()
    {
        return false;
    }
}