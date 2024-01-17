<?php

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

DB::table('settings')->updateOrInsert(['code' => 'marketplace_general_abandoned_cart_email_after'], [
    'type' => 'NUMBER',
    'category' => 'Marketplace',
    'label' => 'Marketplace abandoned cart email after (hours)',
    'value' => '3',
    'editable' => 1,
    'hidden' => 0,
    'created_at' => now(),
    'updated_at' => now(),
]);


NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.abandoned_cart'], [
    'friendly_name' => 'Marketplace Abandoned Cart',
    'title' => 'Check what you missed here!!',
    'body' => [
        'mail' => '<p>Hi&nbsp;<b id="shortcode_name">{name},</b></p><p><b>check what you missed,</b></p><p><a href="{abandoned_cart_link}" target="_blank"><b>Open your missed cart</b></a></p>',
    ],
    'via' => ["mail"]
]);

DB::statement('ALTER TABLE `marketplace_user_cart` MODIFY COLUMN `user_id` int(10) UNSIGNED NULL');

Schema::table('marketplace_user_cart', function (Blueprint $table) {
    $table->string('email')->after('user_id')->nullable()->index();
    $table->boolean('abandoned_email_sent')->default(false)->after('cart');
});
