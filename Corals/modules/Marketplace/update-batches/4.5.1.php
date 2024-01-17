<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('marketplace_stores', function (Blueprint $table) {
    $table->string('code')->after('name')->nullable()->index();
});
