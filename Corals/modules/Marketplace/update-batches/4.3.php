<?php

use Carbon\Carbon;
use Corals\Modules\Marketplace\database\seeds\MarketplacePackagesDatabaseSeeder;
use Corals\User\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Schema::create('marketplace_shipping_packages', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('store_id')->nullable()->index();
    $table->string('name');
    $table->string('dimension_template')->nullable();
    $table->decimal('length', 10, 4)->nullable();
    $table->decimal('width', 10, 4)->nullable();
    $table->decimal('height', 10, 4)->nullable();
    $table->string('distance_unit')->nullable();

    $table->decimal('weight', 10, 4)->nullable();
    $table->string('mass_unit')->nullable();

    $table->string('integration_id')->nullable();

    $table->text('description')->nullable();
    $table->text('properties')->nullable();

    $table->unsignedInteger('created_by')->nullable()->index();
    $table->unsignedInteger('updated_by')->nullable()->index();
    $table->timestamps();

    $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
});

Schema::table('marketplace_shippings', function (Blueprint $table) {
    $table->unsignedInteger('product_id')
        ->nullable()->index()->after('properties');

    $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade')->onUpdate('cascade');
});

$shipping_menu = DB::table('menus')
    ->where('url', config('marketplace.models.shipping.resource_url'))->first();

if ($shipping_menu) {
    $vendor_role = Role::where('name', 'vendor')->first();

    DB::table('menus')->insert([
        'parent_id' => $shipping_menu->parent_id,
        'key' => null,
        'url' => config('marketplace.models.package.resource_url'),
        'active_menu_url' => config('marketplace.models.package.resource_url'),
        'name' => 'Package Templates',
        'description' => 'Packages Listing Menu Item',
        'icon' => 'fa fa-cube',
        'target' => null,
        'roles' => '["1","' . $vendor_role->id . '"]',
        'order' => 0
    ]);
}

DB::table('permissions')->insert([
    [
        'name' => 'Marketplace::package.view',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Marketplace::package.create',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Marketplace::package.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Marketplace::package.delete',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);

$vendor_role = Role::where('name', 'vendor')->first();

$vendor_role->forgetCachedPermissions();

$vendor_role->givePermissionTo('Marketplace::package.view');
$vendor_role->givePermissionTo('Marketplace::package.create');
$vendor_role->givePermissionTo('Marketplace::package.update');
$vendor_role->givePermissionTo('Marketplace::package.delete');

$seeder = new MarketplacePackagesDatabaseSeeder();

$seeder->run();
