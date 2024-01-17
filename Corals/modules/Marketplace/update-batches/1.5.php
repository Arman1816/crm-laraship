<?php

if (!\Schema::hasTable('marketplace_user_cart')) {
    Schema::create('marketplace_user_cart', function (\Illuminate\Database\Schema\Blueprint $table) {

        $table->increments('id');
        $table->integer('user_id')->unsigned()->index();
        $table->string('instance_id')->index();
        $table->text('cart')->nullable();
        $table->timestamps();
        $table->unsignedInteger('created_by')->nullable()->index();
        $table->unsignedInteger('updated_by')->nullable()->index();
        $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
    });
}

if (\Schema::hasTable('marketplace_products') && !\Schema::hasColumn('marketplace_products', 'classification_price')) {
    \Schema::table('marketplace_products', function ($table) {
        $table->string('classification_price')->nullable();

    });
}


if (\Schema::hasTable('marketplace_products') && !\Schema::hasColumn('marketplace_products', 'demo_url')) {
    \Schema::table('marketplace_products', function ($table) {
        $table->string('demo_url')->nullable();

    });
}

