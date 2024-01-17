<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


Schema::table('marketplace_brands', function (Blueprint $table) {
    $table->unsignedInteger('store_id')->nullable()->index()->after('status');;
    $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
});

Schema::table('marketplace_attributes', function (Blueprint $table) {
    $table->unsignedInteger('store_id')->nullable()->index()->after('display_order');;
    $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
});


Schema::table('marketplace_attribute_sets', function (Blueprint $table) {
    $table->unsignedInteger('store_id')->nullable()->index()->after('is_default');;
    $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
});





$vendor_role = \Corals\User\Models\Role::where('name', 'vendor')->first();

$vendor_role->forgetCachedPermissions();
$vendor_role->givePermissionTo('Marketplace::brand.view');
$vendor_role->givePermissionTo('Marketplace::brand.create');
$vendor_role->givePermissionTo('Marketplace::brand.update');
$vendor_role->givePermissionTo('Marketplace::brand.delete');

$vendor_role->givePermissionTo('Marketplace::attribute.view');
$vendor_role->givePermissionTo('Marketplace::attribute.create');
$vendor_role->givePermissionTo('Marketplace::attribute.update');
$vendor_role->givePermissionTo('Marketplace::attribute.delete');


