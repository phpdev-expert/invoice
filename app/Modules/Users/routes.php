<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => 'auth.admin', 'namespace' => 'FI\Modules\Users\Controllers'], function ()
{
    Route::get('users', ['uses' => 'UserController@index', 'as' => 'users.index']);

    Route::get('users/create', ['uses' => 'UserController@create', 'as' => 'users.create']);
    Route::post('users/create', ['uses' => 'UserController@store', 'as' => 'users.store']);

    Route::get('users/{user}/edit', ['uses' => 'UserController@edit', 'as' => 'users.edit']);
    Route::post('users/{user}/edit', ['uses' => 'UserController@update', 'as' => 'users.update']);

    Route::get('users/{user}/delete', ['uses' => 'UserController@delete', 'as' => 'users.delete']);

    Route::get('users/{user}/password/edit', ['uses' => 'UserPasswordController@edit', 'as' => 'users.password.edit']);
    Route::post('users/{user}/password/edit', ['uses' => 'UserPasswordController@update', 'as' => 'users.password.update']);

    Route::post('users/ajax/modal_lookup', ['uses' => 'UserController@ajaxModalLookup', 'as' => 'users.ajax.modalLookup']);
});