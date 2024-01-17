<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$tables = [
    'sliders',
    'slides',
];

foreach ($tables as $tableName) {
    if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'properties')) {
        Schema::table($tableName, function (Blueprint $table) {
            $table->text('properties')->nullable();
        });
    }
}
