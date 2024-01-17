<?php

Route::group(['prefix' => 'payments'], function () {
    Route::get('settings', 'PaymentsController@settings');
    Route::post('settings', 'PaymentsController@saveSettings');
});


Route::group(['prefix' => 'my-invoice', 'middleware' => ['signed']], function () {
    Route::get('{invoice}', 'InvoicesController@publicInvoice')
        ->name('publicInvoice');
});

Route::group(['prefix' => 'invoice/payments'], function () {
    Route::get('success/{invoice}', 'InvoicePaymentController@success')
        ->name('successPublicInvoicePayment')
        ->middleware('signed');

    Route::get('{invoice}/pay', 'InvoicePaymentController@publicInvoicePayment')
        ->name('publicInvoicePayment');

    Route::get('{invoice}/download', 'InvoicePaymentController@download');
    Route::post('{invoice}/do-pay', 'InvoicePaymentController@doPay');
    Route::get('gateway-payment/{gateway}/{invoice}', 'InvoicePaymentController@getGatewayPayment');
    Route::get('gateway-payment-token/{gateway}/{invoice}', 'InvoicePaymentController@gatewayPaymentToken');
    Route::get('gateway-check-payment-token/{gateway}', 'InvoicePaymentController@gatewayCheckPaymentToken');
});


Route::get('my-invoices', 'InvoicesController@myInvoices');
Route::post('invoices/bulk-action', 'InvoicesController@bulkAction');
Route::resource('invoices', 'InvoicesController');
Route::get('invoices/{invoice}/download', 'InvoicesController@download');
Route::post('invoices/{invoice}/send-invoice', 'InvoicesController@sendInvoice');
Route::post('webhooks/{gateway?}', 'WebhooksController');
Route::post('currencies/bulk-action', 'CurrenciesController@bulkAction');
Route::resource('currencies', 'CurrenciesController');

Route::group(['prefix' => 'tax'], function () {
    Route::resource('tax-classes', 'TaxClassesController');
    Route::resource('tax-classes.taxes', 'TaxesController');
});

Route::post('transactions/{transaction}/reverse', 'TransactionsController@reversePayout');
Route::post('transactions/bulk-action', 'TransactionsController@bulkAction');
Route::resource('transactions', 'TransactionsController');


Route::group(['prefix' => 'webhook-calls'], function () {
    Route::get('/', 'WebhooksController@webhookCalls');
    Route::post('{webhookCall}/process', 'WebhooksController@Process');
    Route::post('bulk-action', 'WebhooksController@bulkAction');
});



