<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasColumn('marketplace_products', 'total_sales')) {
    Schema::table('marketplace_products', function (Blueprint $table) {
        $table->integer('total_sales')
            ->after('type')
            ->default(0)
            ->index();
    });
}
