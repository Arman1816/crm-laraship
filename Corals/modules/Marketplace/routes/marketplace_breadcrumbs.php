<?php

// Marketplace
Breadcrumbs::register('marketplace', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Marketplace::module.marketplace.title'));
});

Breadcrumbs::register('marketplace_cart', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.cart.title'), url('marketplace/cart'));
});

Breadcrumbs::register('marketplace_orders', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.order.title'));
});

Breadcrumbs::register('marketplace_wishlist', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.wishlist.title_singular'));
});


Breadcrumbs::register('marketplace_shop', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.shop.title'), url('marketplace/shop'));
});

Breadcrumbs::register('marketplace_settings', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.shop.title'), url('marketplace/settings'));
});

Breadcrumbs::register('marketplace_products', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.product.title'),
        url(config('marketplace.models.product.resource_url')));
});

Breadcrumbs::register('marketplace_product_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_products');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('marketplace_product_show', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_products');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Store

Breadcrumbs::register('marketplace_stores', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.store.title'), url(config('marketplace.models.store.resource_url')));
});

Breadcrumbs::register('store_enroll', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::labels.store.enroll'), url(config('marketplace.models.store.resource_url')));
});


Breadcrumbs::register('marketplace_store_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_stores');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('marketplace_store_show', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_stores');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//SKU
Breadcrumbs::register('marketplace_sku', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('marketplace_products');
    $breadcrumbs->push($product->product_code?:$product->name, $product->getOriginalShowURL());
    $breadcrumbs->push(trans('Marketplace::module.sku.product_title', ['product' => $product->product_code?:$product->name]),
        route(config('marketplace.models.sku.resource_route'), ['product' => $product->hashed_id]));
});

Breadcrumbs::register('marketplace_sku_create_edit', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('marketplace_sku', $product);
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('marketplace_sku_show', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('marketplace_sku', $product);
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Category
Breadcrumbs::register('marketplace_categories', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.category.title'),
        url(config('marketplace.models.category.resource_url')));
});


//Coupon
Breadcrumbs::register('marketplace_coupon_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_coupons');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('marketplace_coupons', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.coupon.title'),
        url(config('marketplace.models.coupon.resource_url')));
});

//Shippings
Breadcrumbs::register('marketplace_shipping_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_shippings');
    $breadcrumbs->push(view()->shared('title_singular'));
});


Breadcrumbs::register('marketplace_shipping_upload', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_shippings');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('marketplace_shippings', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.shipping.title'),
        url(config('marketplace.models.shipping.resource_url')));
});

Breadcrumbs::register('marketplace_package_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_packages');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('marketplace_packages', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.package.title'),
        url(config('marketplace.models.package.resource_url')));
});

Breadcrumbs::register('marketplace_category_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_categories');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Tag
Breadcrumbs::register('marketplace_tags', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.tag.title'), url(config('marketplace.models.tag.resource_url')));
});

Breadcrumbs::register('marketplace_tag_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_tags');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//attribute
Breadcrumbs::register('marketplace_attributes', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.attribute.title_singular'),
        url(config('marketplace.models.attribute.resource_url')));
});

Breadcrumbs::register('marketplace_attribute_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_attributes');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Brand
Breadcrumbs::register('marketplace_brands', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.brand.title'), url(config('marketplace.models.brand.resource_url')));
});

Breadcrumbs::register('marketplace_brand_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_brands');
    $breadcrumbs->push(view()->shared('title_singular'));
});


// attribute-set
Breadcrumbs::register('marketplace_attribute_sets', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace');
    $breadcrumbs->push(trans('Marketplace::module.attribute_set.title_singular'),
        url(config('marketplace.models.attribute_set.resource_url')));
});

Breadcrumbs::register('marketplace_attribute_set_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('marketplace_attribute_sets');
    $breadcrumbs->push(view()->shared('title_singular'));
});
