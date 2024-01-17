<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$tables = [
    'marketplace_sku',
    'marketplace_brands',
    'marketplace_stores',
    'marketplace_categories',
    'marketplace_attributes',
    'marketplace_tags',
    'marketplace_coupons',
    'marketplace_shippings',
];

foreach ($tables as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}
