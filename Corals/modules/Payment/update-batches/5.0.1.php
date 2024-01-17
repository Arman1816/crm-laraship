<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('invoices', function (Blueprint $table) {
    $table->string('reference_id')->nullable()->after('code');
});
