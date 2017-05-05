<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => 'auth.admin', 'prefix' => 'clients', 'namespace' => 'FI\Modules\Clients\Controllers'], function ()
{
    Route::get('/', ['uses' => 'ClientController@index', 'as' => 'clients.index']);
    Route::get('create', ['uses' => 'ClientController@create', 'as' => 'clients.create']);
    Route::get('{client}/edit', ['uses' => 'ClientController@edit', 'as' => 'clients.edit']);
    Route::get('{client}', ['uses' => 'ClientController@show', 'as' => 'clients.show']);
    Route::get('{client}/delete', ['uses' => 'ClientController@delete', 'as' => 'clients.delete']);
    Route::get('ajax/lookup', ['uses' => 'ClientController@ajaxLookup', 'as' => 'clients.ajax.lookup']);

    Route::post('create', ['uses' => 'ClientController@store', 'as' => 'clients.store']);
    Route::post('ajax/modal_edit', ['uses' => 'ClientController@ajaxModalEdit', 'as' => 'clients.ajax.modalEdit']);
    Route::post('ajax/modal_lookup', ['uses' => 'ClientController@ajaxModalLookup', 'as' => 'clients.ajax.modalLookup']);
    Route::post('ajax/modal_update/{client}', ['uses' => 'ClientController@ajaxModalUpdate', 'as' => 'clients.ajax.modalUpdate']);
    Route::post('ajax/check_name', ['uses' => 'ClientController@ajaxCheckName', 'as' => 'clients.ajax.checkName']);
    Route::post('ajax/check_duplicate_name', ['uses' => 'ClientController@ajaxCheckDuplicateName', 'as' => 'clients.ajax.checkDuplicateName']);
    Route::post('{client}/edit', ['uses' => 'ClientController@update', 'as' => 'clients.update']);
});