<?php

\DB::table('settings')->insert([
    [
        'code' => 'cms_comments_enabled',
        'type' => 'BOOLEAN',
        'category' => 'CMS',
        'label' => 'Comments Enabled',
        'value' => 'true',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'code' => 'cms_comments_require_approval',
        'type' => 'BOOLEAN',
        'category' => 'CMS',
        'label' => 'Comments Require Approval',
        'value' => 'false',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
