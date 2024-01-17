<?php

//Add Module Currency Permission
\DB::table('permissions')->insert([
    [
        'name' => 'Payment::currency.update',
        'guard_name' => config('auth.defaults.guard'),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ],
]);