<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

\DB::table('settings')->insert([
    [
        'code' => 'cms_comments_allow_guest',
        'type' => 'BOOLEAN',
        'category' => 'CMS',
        'label' => 'Comments Allow Guest ',
        'value' => 'false',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]
]);


Schema::table('cms_blocks', function (Blueprint $table) {
    $table->boolean('as_row')
        ->after('key')
        ->default(true);
});
