<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['namespace' => 'FI\Modules\Merchant\Controllers'], function ()
{
    Route::get('merchant/invoice/{urlKey}/pay', ['uses' => 'MerchantController@invoicePay', 'as' => 'merchant.invoice.pay']);
    Route::get('merchant/invoice/{urlKey}/return', ['uses' => 'MerchantController@invoiceReturn', 'as' => 'merchant.invoice.return']);
    Route::post('merchant/invoice/{urlKey}/notify', ['uses' => 'MerchantController@invoiceNotify', 'as' => 'merchant.invoice.notify']);
    Route::get('merchant/invoice/{urlKey}/cancel', ['uses' => 'MerchantController@invoiceCancel', 'as' => 'merchant.invoice.cancel']);
    Route::post('merchant/invoice/{urlKey}/modal_cc', ['uses' => 'MerchantController@invoiceModalCc', 'as' => 'merchant.invoice.modalCc']);
    Route::post('merchant/invoice/{urlKey}/validate', ['uses' => 'MerchantController@invoicePay', 'as' => 'merchant.invoice.payCc']);
});