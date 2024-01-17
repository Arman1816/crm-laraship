<?php


\Schema::table('marketplace_orders', function (\Illuminate\Database\Schema\Blueprint $table) {

    $table->unsignedInteger('user_id')->nullable()->change();

});

\DB::table('settings')->insert([
    [
        'code' => 'marketplace_checkout_guest_checkout',
        'type' => 'BOOLEAN',
        'category' => 'Marketplace',
        'label' => 'Marketplace checkout guest',
        'value' => 'true',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]]);
