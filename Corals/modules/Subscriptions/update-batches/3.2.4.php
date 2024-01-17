<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('feature_plan', function (Blueprint $table) {
    $table->text('plan_caption')->nullable()->change();
});
