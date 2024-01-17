<?php

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('plans', function (Blueprint $table) {
    $table->integer('notify_end_subscription_before')
        ->after('free_plan')
        ->nullable();
});


Schema::table('invoice_items', function (Blueprint $table) {
    $table->dropUnique('invoice_items_code_unique');
});


NotificationTemplate::updateOrCreate(['name' => 'notifications.subscription.subscription_renewal_notification'], [
    'friendly_name' => 'Subscription Renewal',
    'title' => '{plan_name} subscription will ends in {remaining_days}',
    'body' => [
        'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Dear {user},</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
Your Subscription <b>{plan_name}</b> plan will ends in {remaining_days}.
<br/>
Your subscription details:<br/>
    - subscription reference: {reference},<br/> 
    - subscription created at: {created_at},<br/> 
    - subscription plan name: {plan_name} - {product_name},<br/> 
    - subscription plan price: {plan_price},<br/> 
    - subscription plan bill frequency: {plan_frequency},<br/> 
    - subscription plan bill cycle: {plan_cycle},<br/> 
<br/>
<br/>
Thanks.
</p></td></tr><tr><td align="center" style="padding: 10px 0 25px 0;"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" bgcolor="#ed8e20" style="border-radius: 5px;"><a href="{dashboard_link}" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;" target="_blank">Visit your Dashboard</a></td></tr></tbody></table></td></tr></tbody></table>',
        'database' => 'Your <b>{plan_name}</b> plan will end in {remaining_days}'
    ],
    'via' => ["mail", "database", "user_preferences"]
]);

NotificationTemplate::updateOrCreate(['name' => 'notifications.subscription.subscription_renewal_invoice'],
    [
        'friendly_name' => 'Subscription Renewal Invoice',
        'title' => '{plan_name} subscription renewal invoice',
        'body' => [
            'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Dear {user},</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
Your Subscription <b>{plan_name}</b> renewal invoice.
<br/>
check the following invoice link.
<br/>
<a href="{invoice_public_link}">
{invoice_public_link}
</a>
<br/>
{gatewayPaymentDetails}
<br/>
Thanks.
</p></td></tr><tr><td align="center" style="padding: 10px 0 25px 0;"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="center" bgcolor="#ed8e20" style="border-radius: 5px;"><a href="{dashboard_link}" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;" target="_blank">Visit your Dashboard</a></td></tr></tbody></table></td></tr></tbody></table>',
        ],
        'via' => ["mail", "user_preferences"]
    ]);
