<?php


if ( !\Schema::hasColumn('invoices', 'properties')) {
    Illuminate\Support\Facades\Schema::table('invoices', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->text('properties')->nullable()->after('status');
    });
}

\DB::statement('ALTER TABLE `invoices` MODIFY `user_id` INTEGER UNSIGNED NULL;');


