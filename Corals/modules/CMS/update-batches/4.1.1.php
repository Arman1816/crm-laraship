<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('cms_widgets', function (Blueprint $table) {
    $table->unsignedInteger('widget_width')->nullable()->change();
});
