<?php

//Add Settings News Ticker
\DB::table('settings')->insert([
    [
        'code' => 'feed_url_rss',
        'type' => 'TEXT',
        'category' => 'CMS',
        'label' => 'Feed Url',
        'value' => null,
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'code' => 'rss_to_json_Api_Key',
        'type' => 'TEXT',
        'category' => 'CMS',
        'label' => 'Rss to Json Api Key',
        'value' => 'aoqoefowy4hyn3qgsmodmozxqagxvxz7wtnduko6',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);