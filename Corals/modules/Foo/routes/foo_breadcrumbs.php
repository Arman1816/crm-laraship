<?php

//Bar
Breadcrumbs::register('foo_bars', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Foo::module.bar.title'), url(config('foo.models.bar.resource_url')));
});

Breadcrumbs::register('foo_bar_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('foo_bars');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('foo_bar_show', function ($breadcrumbs) {
    $breadcrumbs->parent('foo_bars');
    $breadcrumbs->push(view()->shared('title_singular'));
});