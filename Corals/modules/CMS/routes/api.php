<?php

Route::apiResource('categories', 'CategoriesController', ['except' => ['show'], 'as' => 'api.cms']);
Route::apiResource('faqs', 'FaqsController', ['except' => ['show'], 'as' => 'api.cms']);
Route::apiResource('news', 'NewsController', ['except' => ['show'], 'as' => 'api.cms']);
Route::apiResource('pages', 'PagesController', ['except' => ['show'], 'as' => 'api.cms']);
Route::apiResource('posts', 'PostsController', ['except' => ['show'], 'as' => 'api.cms']);

Route::group(['prefix' => 'internal'], function () {
    Route::get('content-by-type/{type}', 'CMSInternalController@getContentListByType')->name('api.cms.internal.content-by-type');
    Route::get('posts-by-category/{slug}', 'CMSInternalController@getPostsByCategory')->name('api.cms.internal.posts-by-category');
    Route::get('posts-by-tag/{slug}', 'CMSInternalController@getPostsByTag')->name('api.cms.internal.posts-by-tag');
    Route::get('{slug}', 'CMSInternalController@show')->name('api.cms.internal.get_by_slug');
});

Route::group([], function () {
    Route::get('content-by-type/{type}', 'CMSPublicController@getContentListByType')->name('api.cms.content-by-type');
    Route::get('posts-by-category/{slug}', 'CMSPublicController@getPostsByCategory')->name('api.cms.posts-by-category');
    Route::get('posts-by-tag/{slug}', 'CMSPublicController@getPostsByTag')->name('api.cms.posts-by-tag');
    Route::get('{slug}', 'CMSPublicController@show')->name('api.cms.get_by_slug');
});