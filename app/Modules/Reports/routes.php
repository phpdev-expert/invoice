<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Route::group(['middleware' => 'auth.admin', 'namespace' => 'FI\Modules\Reports\Controllers'], function ()
{
    Route::get('reports/client_statement', ['uses' => 'ClientStatementReportController@index', 'as' => 'reports.clientStatement']);
    Route::post('reports/client_statement/validate', ['uses' => 'ClientStatementReportController@ajaxValidate', 'as' => 'reports.clientStatement.ajax.validate']);
    Route::get('reports/client_statement/html', ['uses' => 'ClientStatementReportController@html', 'as' => 'reports.clientStatement.html']);
    Route::get('reports/client_statement/pdf', ['uses' => 'ClientStatementReportController@pdf', 'as' => 'reports.clientStatement.pdf']);

    Route::get('reports/item_sales', ['uses' => 'ItemSalesReportController@index', 'as' => 'reports.itemSales']);
    Route::post('reports/item_sales/validate', ['uses' => 'ItemSalesReportController@ajaxValidate', 'as' => 'reports.itemSales.ajax.validate']);
    Route::get('reports/item_sales/html', ['uses' => 'ItemSalesReportController@html', 'as' => 'reports.itemSales.html']);
    Route::get('reports/item_sales/pdf', ['uses' => 'ItemSalesReportController@pdf', 'as' => 'reports.itemSales.pdf']);

    Route::get('reports/payments_collected', ['uses' => 'PaymentsCollectedReportController@index', 'as' => 'reports.paymentsCollected']);
    Route::post('reports/payments_collected/validate', ['uses' => 'PaymentsCollectedReportController@ajaxValidate', 'as' => 'reports.paymentsCollected.ajax.validate']);
    Route::get('reports/payments_collected/html', ['uses' => 'PaymentsCollectedReportController@html', 'as' => 'reports.paymentsCollected.html']);
    Route::get('reports/payments_collected/pdf', ['uses' => 'PaymentsCollectedReportController@pdf', 'as' => 'reports.paymentsCollected.pdf']);

    Route::get('reports/revenue_by_client', ['uses' => 'RevenueByClientReportController@index', 'as' => 'reports.revenueByClient']);
    Route::post('reports/revenue_by_client/validate', ['uses' => 'RevenueByClientReportController@ajaxValidate', 'as' => 'reports.revenueByClient.ajax.validate']);
    Route::get('reports/revenue_by_client/html', ['uses' => 'RevenueByClientReportController@html', 'as' => 'reports.revenueByClient.html']);
    Route::get('reports/revenue_by_client/pdf', ['uses' => 'RevenueByClientReportController@pdf', 'as' => 'reports.revenueByClient.pdf']);

    Route::get('reports/tax_summary', ['uses' => 'TaxSummaryReportController@index', 'as' => 'reports.taxSummary']);
    Route::post('reports/tax_summary/validate', ['uses' => 'TaxSummaryReportController@ajaxValidate', 'as' => 'reports.taxSummary.ajax.validate']);
    Route::get('reports/tax_summary/html', ['uses' => 'TaxSummaryReportController@html', 'as' => 'reports.taxSummary.html']);
    Route::get('reports/tax_summary/pdf', ['uses' => 'TaxSummaryReportController@pdf', 'as' => 'reports.taxSummary.pdf']);
});