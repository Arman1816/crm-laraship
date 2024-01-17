<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('marketplace_products', function (Blueprint $table) {
    $table->text('price_per_quantity')->after('offers')->nullable();
});
