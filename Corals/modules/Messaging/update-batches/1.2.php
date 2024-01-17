<?php


$messaging_menu = \DB::table('menus')->where([
    'parent_id' => 1,// admin
    'key' => 'messaging',

])->first();

$messaging_menu_id = $messaging_menu->id;

\DB::table('menus')->insert([
    [
        'parent_id' => $messaging_menu_id,
        'key' => null,
        'url' => 'messaging/discussions/all',
        'active_menu_url' =>  'messaging/discussions*',
        'name' => 'All Discussions',
        'description' => 'All Discussions List Menu Item',
        'icon' => 'fa fa-comments-o',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
]);


\DB::table('permissions')->insert([
    //add permissions all discussion
    [
        'name' => 'Messaging::discussion.view_all',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],

]);


