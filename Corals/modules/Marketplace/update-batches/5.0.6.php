<?php


use Corals\Menu\Models\Menu;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Schema::create('marketplace_attribute_sets', function (Blueprint $table) {
    $table->increments('id');
    $table->string('code');
    $table->string('name');
    $table->boolean('is_default')->default(false);

    $table->longText('properties')->nullable();

    $table->unsignedInteger('created_by')->nullable()->index();
    $table->unsignedInteger('updated_by')->nullable()->index();

    $table->softDeletes();
    $table->timestamps();
});

Schema::create('marketplace_set_has_models', function (Blueprint $table) {
    $table->increments('id');

    $table->unsignedInteger('set_id');
    $table->unsignedInteger('model_id');
    $table->string('model_type');

    $table->longText('properties')->nullable();

    $table->timestamps();

    $table->foreign('set_id')
        ->references('id')
        ->on('marketplace_attribute_sets')
        ->onDelete('cascade')
        ->onUpdate('cascade');
});


$marketplaceMenu = Menu::query()->where('key', 'marketplace')->first();

DB::table('menus')->insert([
    [
        'parent_id' => $marketplaceMenu->id,
        'key' => null,
        'url' => config('marketplace.models.attribute_set.resource_url'),
        'active_menu_url' => config('marketplace.models.attribute_set.resource_url') . '*',
        'name' => 'Attribute Sets',
        'description' => 'Attribute Sets List Menu Item',
        'icon' => 'fa fa-sliders',
        'target' => null,
        'roles' => '["1"]',
        'order' => 0
    ],
]);
Schema::table('marketplace_sku_options', function (Blueprint $table) {
    $table->unsignedInteger('product_id')->nullable()->index()->after('sku_id');
    $table->unsignedInteger('sku_id')->nullable()->change();

    $table->foreign('product_id')->references('id')->on('marketplace_products')->onUpdate('cascade')->onDelete('cascade');
});
