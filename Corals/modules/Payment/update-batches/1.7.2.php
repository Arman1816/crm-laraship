<?php

\Schema::table('invoices', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->text('terms')->nullable()->after('description');
    $table->boolean('is_sent')->nullable()->default(false)->after('invoicable_id');
    $table->dateTime('invoice_date')->after('due_date');
});

\Schema::table('invoice_items', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->integer('quantity')->nullable()->default(1)->after('amount');
});

\DB::table('notification_templates')->updateOrInsert(['name' => 'notifications.invoice.send_invoice'], [
    'title' => 'Invoice {invoice_code}',
    'friendly_name' => 'Send Invoice Notification',
    'body' => '{"mail":"<p>Dear {user_name},<\/p>\r\n\r\n<p>Please find the attached invoice, We would appreciate your prompt payment.<\/p>\r\n\r\n<p><b>{invoice_code}<\/b><\/p>\r\n\r\n<p>Order: {invoicable_identifier}&nbsp;<p>&nbsp;<\/p>\r\n\r\n<p>Regards,<\/p>","database":null}',
    'extras' => '[]',
    'via' => '["mail"]',
]);
