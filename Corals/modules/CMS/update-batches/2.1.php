<?php

//Add Settings News Ticker
\DB::table('settings')->insert([
    [
        'code' => 'number_of_news_item_show',
        'type' => 'TEXT',
        'category' => 'CMS',
        'label' => 'Number of News Item Show',
        'value' => 5,
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
    [
        'code' => 'enable_news_ticker',
        'type' => 'BOOLEAN',
        'category' => 'CMS',
        'label' => 'Enable News Ticker',
        'value' => 'true',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ]
]);