<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('marketplace_products', function (Blueprint $table) {
    $table->text('offers')->nullable()->after('description');
});
