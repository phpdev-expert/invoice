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

class Mollie
{
    public static function createGateway()
    {
        $gateway = Omnipay::create('Mollie');

        $merchant = json_decode(config('fi.merchant'));

        $gateway->setApiKey($merchant->Mollie->apiKey);

        return $gateway;
    }

    public static function setPurchaseParameters($purchaseParameters, $params)
    {
        $purchaseParameters['returnUrl'] = route('merchant.invoice.return', [$params['urlKey'], 'merchant' => 'Mollie']);
        $purchaseParameters['cancelUrl'] = route('merchant.invoice.cancel', [$params['urlKey']]);
        $purchaseParameters['notifyUrl'] = route('merchant.invoice.notify', [$params['urlKey'], 'merchant' => 'Mollie']);

        return $purchaseParameters;
    }

    public static function isRedirect()
    {
        return true;
    }

    public static function isNotify()
    {
        return true;
    }
}