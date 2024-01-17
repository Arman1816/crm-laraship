<?php
Route::group(['prefix' => 'subscription'], function () {
    Route::get('pricing/{product?}', 'SubscriptionsController@pricing')->name('api.subscription.pricing');
    Route::get('pricing-public/{product?}', 'SubscriptionsController@pricingPublic')->name('api.subscription.pricing-public');
    Route::post('subscribe/{plan}', 'SubscriptionsController@subscribe')->name('api.subscription.subscribe');
    Route::post('cancel-plan/{plan}', 'SubscriptionsController@cancelPlanSubscription')->name('api.subscription.cancel-plan');
});
