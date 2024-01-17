<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Schema::table('marketplace_order_items', function (Blueprint $table) {
    $table->decimal('subtotal')->after('quantity')->default(0);
});

DB::table('marketplace_order_items')
    ->where('type', 'Product')
    ->update(['subtotal' => DB::raw('amount * quantity')]);

DB::table('marketplace_order_items')
    ->where('type', '<>', 'Product')
    ->update(['subtotal' => DB::raw('amount')]);
