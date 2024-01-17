<?php

Route::group(['prefix' => 'marketplace', 'as' => 'api.marketplace.'], function () {
    Route::apiResource('attributes', 'AttributesController', ['except' => 'show']);
    Route::apiResource('categories', 'CategoriesController', ['except' => 'show']);
    Route::apiResource('brands', 'BrandsController', ['except' => 'show']);
    Route::apiResource('coupons', 'CouponsController', ['except' => 'show']);
    Route::apiResource('tags', 'TagsController', ['except' => 'show']);
    Route::apiResource('shippings', 'ShippingsController', ['except' => 'show']);
    Route::apiResource('packages', 'PackagesController', ['except' => 'show']);

    Route::apiResource('products', 'ProductsController');
    Route::apiResource('products.sku', 'SKUController');

    Route::group(['prefix' => 'store', 'as' => 'store.'], function () {
        Route::get('settings', 'StoresController@getSettings')->name('get-settings');
        Route::put('settings/{store}', 'StoresController@saveSettings')->name('save-settings');
    });

    Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
        Route::get('single-product/{product}', 'ShopController@singleProduct')->name('single-product');
        Route::get('products-list', 'ShopController@productsList')->name('products-list');
        Route::get('settings', 'ShopController@settings')->name('settings');
        Route::post('{product}/rate', ['as' => 'rate-product', 'uses' => 'RatingController@createRating']);
        Route::post('{product}/create-comment', 'CommentController@createComment')->name('product-create-comment');
        Route::post('{comment}/create-reply', 'CommentController@replyComment')->name('product-reply-comment');
    });

    Route::group(['prefix' => 'checkout-public', 'as' => 'checkout-public.'], function () {
        Route::get('get-coupon-by-code/{code}', 'CheckoutPublicController@getCouponByCode')->name('get-coupon-by-code');
        Route::get('get-shipping-roles/{country_code}/{store_id}', 'CheckoutPublicController@getAvailableShippingRoles')->name('get-shipping-roles');
    });

    Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () {
        Route::get('get-coupon-by-code/{code}', 'CheckoutController@getCouponByCode')->name('get-coupon-by-code');
        Route::get('get-shipping-roles/{country_code}/{store_id}', 'CheckoutController@getAvailableShippingRoles')->name('get-shipping-roles');
        Route::post('order-submit', 'CheckoutController@orderSubmit')->name('order-submit');
    });

    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
        Route::get('store-orders', 'OrdersController@storeOrders')->name('store-orders');
        Route::get('my-orders', 'OrdersController@myOrders')->name('my-orders');
        Route::get('{order}/download/{mediaId}', 'OrdersController@downloadFile')->name('order-download');
        Route::get('orders/{order}/track/{tracking_number}', 'OrdersController@track')->name('order-tracking');

    });

    Route::apiResource('orders', 'OrdersController', ['only' => ['show']]);

    Route::group(['prefix' => 'wishlist'], function () {
        Route::post('{product}', 'WishlistController@setWishlist')->name('set-wishlist');
        Route::get('my', ['as' => 'my-wishlist', 'uses' => 'WishlistController@myWishlist']);
    });

    Route::group(['prefix' => 'follow'], function () {
        Route::post('{store}', 'FollowController@setWishlist')->name('set-follow-list');
        Route::get('my', ['as' => 'my-follow-list', 'uses' => 'FollowController@myWishlist']);
    });
});
