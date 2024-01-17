<?php

\Schema::table('marketplace_order_items', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->text('properties')->nullable()->after('order_id');
});
