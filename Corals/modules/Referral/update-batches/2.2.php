<?php

\DB::table('settings')->insert([
    [
        'code' => 'referral_point_value',
        'type' => 'NUMBER',
        'category' => 'Referral',
        'label' => 'Value per reword point',
        'value' => '0.1',
        'editable' => 1,
        'hidden' => 0,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);
