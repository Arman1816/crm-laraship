<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = [
    'features',
    'plans',
    'products',
    'subscriptions'
];

foreach ($tables as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}

$subscriptions_menu = DB::table('menus')->where('key', 'subscriptions')->first();

// seed subscriptions children menu
DB::table('menus')->insert([
    [
        'parent_id' => $subscriptions_menu->id,
        'key' => null,
        'url' => config('subscriptions.models.subscription_cycle.resource_url'),
        'active_menu_url' => config('subscriptions.models.subscription_cycle.resource_url') . '*',
        'name' => 'Subscription Cycles',
        'description' => 'Subscriptions Cycles List Menu Item',
        'icon' => 'fa  fa-circle-o-notch',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
    [
        'parent_id' => $subscriptions_menu->id,
        'key' => null,
        'url' => config('subscriptions.models.plan_usage.resource_url'),
        'active_menu_url' => config('subscriptions.models.plan_usage.resource_url') . '*',
        'name' => 'Plan Usage',
        'description' => 'Plan Usage List Menu Item',
        'icon' => 'fa fa-battery-quarter',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
]);
