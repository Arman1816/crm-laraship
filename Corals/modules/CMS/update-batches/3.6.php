<?php

if (!\Schema::hasColumn('posts', 'properties')) {
    \Schema::table('posts', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('properties')->nullable();
    });
}

if (!\Schema::hasColumn('categories', 'properties')) {
    \Schema::table('categories', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('properties')->nullable();
    });
}
