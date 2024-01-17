<?php

Route::group(['prefix' => 'utilities'], function () {
    Route::group(['prefix' => 'address'], function () {
        Route::apiResource('locations', 'Address\LocationsController', ['as' => 'api.utilities.address']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::apiResource('attributes', 'Category\AttributesController', ['as' => 'api.utilities.category']);
        Route::apiResource('categories', 'Category\CategoriesController', ['as' => 'api.utilities.category']);
    });

    Route::group(['prefix' => 'tag'], function () {
        Route::apiResource('tags', 'Tag\TagsController', ['as' => 'api.utilities.tag']);
    });

    Route::delete('wishlist/{wishlist}', 'Wishlist\WishlistAPIBaseController@destroy')->name('api.utilities.wishlist.destroy');
});