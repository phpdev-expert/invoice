<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => 'auth.admin', 'namespace' => 'FI\Modules\Payments\Controllers'], function ()
{
    Route::get('payments', ['uses' => 'PaymentController@index', 'as' => 'payments.index']);
    Route::get('payments/{payment}/invoice/{invoice}', ['uses' => 'PaymentController@edit', 'as' => 'payments.edit']);
    Route::get('payments/{payment}/invoice/{invoice}/delete', ['uses' => 'PaymentController@delete', 'as' => 'payments.delete']);

    Route::post('payments/modal/enter_payment', ['uses' => 'PaymentController@modalEnterPayment', 'as' => 'payments.ajax.modalEnterPayment']);
    Route::post('payments/ajax/create', ['uses' => 'PaymentController@ajaxStore', 'as' => 'payments.ajax.store']);
    Route::post('payments/{payment}/invoice/{invoice}/update', ['uses' => 'PaymentController@update', 'as' => 'payments.update']);

    Route::group(['prefix' => 'payment_mail'], function ()
    {
        Route::post('create', ['uses' => 'PaymentMailController@create', 'as' => 'paymentMail.create']);
        Route::post('store', ['uses' => 'PaymentMailController@store', 'as' => 'paymentMail.store']);
    });
});