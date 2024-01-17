<?php


$marketplace_menu = \DB::table('menus')->where([
    'parent_id' => 1,
    'key' => 'marketplace',

])->first();

if($marketplace_menu){
    $marketplace_menu_id = $marketplace_menu->id;

    \DB::table('menus')->insert([
            [
                'parent_id' => $marketplace_menu_id,
                'key' => null,
                'url' => config('marketplace.models.follow.resource_url') . '/my',
                'active_menu_url' => config('marketplace.models.follow.resource_url') . '/my',
                'name' => 'My Following Stores',
                'description' => 'Store Follows',
                'icon' => 'fa fa-star-o',
                'target' => null,
                'roles' => '["2"]',
                'order' => 0
            ],
        ]
    );
}
