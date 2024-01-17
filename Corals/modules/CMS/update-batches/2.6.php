<?php

use Carbon\Carbon;


//Change column value in type
\DB::statement("ALTER TABLE `posts` CHANGE COLUMN `type` `type` ENUM('post', 'page', 'news', 'faq', 'testimonial', 'download') NOT NULL DEFAULT 'post'");


$cms_menu = \DB::table('menus')->where(['parent_id' => 1, 'key' => 'cms'])->first();

$cms_menu_id = $cms_menu->id;

//Menu
\DB::table('menus')->insert([
    [
        'parent_id' => $cms_menu_id,
        'key' => null,
        'url' => 'cms/testimonials',
        'active_menu_url' => 'cms/testimonials*',
        'name' => 'Testimonials',
        'description' => 'Testimonials List Menu Item',
        'icon' => 'fa fa-quote-left',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
    [
        'parent_id' => $cms_menu_id,
        'key' => null,
        'url' => 'cms/downloads',
        'active_menu_url' =>  'cms/downloads*',
        'name' => 'Downloads',
        'description' => 'downloads List Menu Item',
        'icon' => 'fa fa-arrow-circle-down',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ]
]);

//Add Permission
\DB::table('permissions')->insert([

    [
        'name' => 'CMS::testimonial.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'name' => 'CMS::testimonial.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'name' => 'CMS::testimonial.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'name' => 'CMS::testimonial.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    //download
    [
        'name' => 'CMS::download.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'name' => 'CMS::download.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'name' => 'CMS::download.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'name' => 'CMS::download.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);


\DB::table('settings')->insert([
    [
        'code' => 'news_page_slug',
        'type' => 'TEXT',
        'category' => 'CMS',
        'label' => 'News page slug',
        'value' => 'news',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);
