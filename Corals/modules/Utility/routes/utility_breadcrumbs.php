<?php

//location
Breadcrumbs::register('address_locations', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.location.title'), url(config('utility.models.location.resource_url')));
});

Breadcrumbs::register('address_location_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('address_locations');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Tag
Breadcrumbs::register('tags', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.tag.title'), url(config('utility.models.tag.resource_url')));
});

Breadcrumbs::register('tag_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('tags');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Category
Breadcrumbs::register('utility_categories', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.category.title'), url(config('utility.models.category.resource_url')));
});

//Rating
Breadcrumbs::register('utility_ratings', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.rating.title'), url(config('utility.models.rating.resource_url')));
});

Breadcrumbs::register('utility_ratings_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_ratings');
    $breadcrumbs->push(trans('Utility::module.rating.title'), url(config('utility.models.rating.resource_url')));
});
//Comment
Breadcrumbs::register('utility_comments', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.comment.title'), url(config('utility.models.comment.resource_url')));
});

Breadcrumbs::register('utility_category_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_categories');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//attribute
Breadcrumbs::register('utility_attributes', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.attribute.title_singular'), url(config('utility.models.attribute.resource_url')));
});

Breadcrumbs::register('utility_attribute_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_attributes');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('utility_invite_friends_create', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(view()->shared('title'));
});

Breadcrumbs::register('utility_seo_item', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(view()->shared('title'));
});

Breadcrumbs::register('utilities_content_consent', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(view()->shared('title'));
});

//Webhook
Breadcrumbs::register('utility_webhook', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.webhook.title'), url(config('utility.models.webhook.resource_url')));
});

//listOfValue
Breadcrumbs::register('utility_listOfValues', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.listOfValue.title_singular'), url(config('utility.models.listOfValue.resource_url')));
});

Breadcrumbs::register('utility_listOfValue_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_listOfValues');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('utility_guide', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.guide.title'), url(config('utility.models.guide.resource_url')));
});

Breadcrumbs::register('utility_guide_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_guide');
    $breadcrumbs->push(trans('Utility::module.guide.title'), url(config('utility.models.guide.resource_url')));
});
