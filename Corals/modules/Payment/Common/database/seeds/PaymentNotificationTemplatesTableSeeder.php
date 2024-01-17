<?php

namespace Corals\Modules\Payment\Common\database\seeds;

use Illuminate\Database\Seeder;

class PaymentNotificationTemplatesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('notification_templates')->updateOrInsert(['name' => 'notifications.invoice.send_invoice'], [
            'title' => 'Invoice {invoice_code}',
            'friendly_name' => 'Send Invoice Notification',
            'body' => '{"mail":"<p>Dear {user_name},<\/p>\r\n\r\n<p>Please find the attached invoice, We would appreciate your prompt payment.<\/p>\r\n\r\n<p><b>{invoice_code}<\/b><\/p>\r\n\r\n<p>Order: {invoicable_identifier}&nbsp;<p>&nbsp;<\/p>\r\n\r\n<p>Regards,<\/p>","database":null}',
            'extras' => '[]',
            'via' => '["mail"]',
        ]);

    }
}
