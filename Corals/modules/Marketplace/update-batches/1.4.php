<?php


if (\Schema::hasTable('marketplace_products') && !\Schema::hasColumn('marketplace_products', 'visitors_count')) {
    \Schema::table('marketplace_products', function ($table) {
        $table->integer('visitors_count')->default(0);
    });
}