<?php

Route::group(['prefix' => ''], function () {
    Route::get('/', 'SubscriptionsController@index');

    Route::group(['prefix' => 'subscriptions'], function () {

        Route::post('{subscription}/mark-invoice-as-paid-and-active',
            'SubscriptionsController@markInvoiceAsPaidAndActive');

        Route::post('{subscription}/renew', 'SubscriptionsController@renewSubscription');
    });

    Route::resource('subscriptions', 'SubscriptionsController');
    Route::get('subscriptions/{subscription}/create-invoice', 'SubscriptionsController@createInvoice');
    Route::resource('products', 'ProductsController');
    Route::resource('products.plans', 'PlansController');
    Route::post('products/{product}/plans/{plan}/create-gateway-plan', 'PlansController@createGatewayPlan');
    Route::resource('products.features', 'FeaturesController');
    Route::post('products/{product}/features/reorder', 'FeaturesController@reorder');

    Route::get('subscription-cycles/my-subscription-cycles', 'SubscriptionCyclesController@mySubscriptionCycles');
    Route::resource('subscription-cycles', 'SubscriptionCyclesController')->only('index');

    Route::resource('plan-usage', 'PlanUsageController')->only('index');
});

Route::group(['middleware' => \Corals\Modules\Subscriptions\Middleware\SubscriptionMiddleware::class], function () {
    Route::get('select/{product?}', 'SubscriptionsController@pricing');

    Route::get('cancel/{plan}', 'SubscriptionsController@cancel');
    Route::post('cancel/{plan}', 'SubscriptionsController@cancel');

    Route::get('checkout/{plan}', 'SubscriptionsController@checkout');

    Route::get('payment-configuration', 'SubscriptionsController@paymentConfiguration');


    Route::post('save-payment-configuration', 'SubscriptionsController@saveCard');
    Route::post('do-checkout/{plan}', 'SubscriptionsController@doCheckout');


    Route::get('gateway-payment/{gateway}/{plan?}', 'SubscriptionsController@gatewayPayment');
    Route::get('gateway-subscription-token/{gateway}/{plan?}', 'SubscriptionsController@gatewaySubscriptionToken');

    Route::get('status/{subscription?}', 'SubscriptionsController@statusPage');
});
