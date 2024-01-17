<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('marketplace_stores', function (Blueprint $table) {
    $table->text('return_policy')
        ->after('short_description')
        ->nullable();
});
