<?php

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('marketplace_attributes', function (Blueprint $table) {
    $table->string('code')->after('type')->nullable()->unique()->index();
});

Schema::table('marketplace_products', function (Blueprint $table) {
    $table->string('product_code')->after('type')->nullable()->unique()->index();
});


NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.import_status'], [
    'friendly_name' => 'Marketplace Products Import Status',
    'title' => 'Marketplace {import_file_name} Import Status',
    'body' => [
        'mail' => '<p>Hi {user_name},</p><p>Here you are the {import_file_name} Import Status:</p><p>Success Imported Records: <b>{success_records_count}</b></p>
<p style="color:red;">Failed Imported Records: <b>{failed_records_count}</b><br/>{import_log_file}</p>',
    ],
    'via' => ["mail"]
]);
