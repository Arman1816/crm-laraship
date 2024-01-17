<?php

Route::apiResource('currencies', 'CurrenciesController', ['as' => 'api.payment', 'only' => ['index']]);
Route::get('active-currencies', 'CurrenciesController@getActiveCurrenciesList')->name('api.payment.active-currencies');

Route::get('get-settings', 'PaymentsController@getPaymentSettings')->name('api.payment.get-settings');

Route::apiResource('invoices', 'InvoicesController', ['as' => 'api.payment', 'only' => ['index']]);
Route::get('my-invoices', 'InvoicesController@myInvoices')->name('api.payment.my-invoices');

Route::post('calculate-taxes', 'PaymentsController@calculateTaxClassTaxes')->name('api.payment.calculate-taxes');

Route::apiResource('tax-classes', 'TaxClassesController', ['as' => 'api.payment', 'only' => ['index']]);
Route::apiResource('tax-classes.taxes', 'TaxesController', ['as' => 'api.payment', 'only' => ['index']]);
