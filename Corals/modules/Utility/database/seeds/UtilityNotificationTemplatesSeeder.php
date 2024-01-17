<?php

namespace Corals\Modules\Utility\database\seeds;

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class UtilityNotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationTemplate::updateOrCreate(['name' => 'notifications.rate.rate_created'], [
            'friendly_name' => 'New Rating Created',
            'title' => 'New Rating has been created',
            'extras' => [
                "bcc_users" => ["1"]
            ],
            'body' =>  [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello there,</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
New Rating for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has been created by {author_name} ({author_email}).
<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>

<br/>
Thanks.
</p></td></tr></tbody></table>',
                'database' => 'New Rating for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has been created by {author_name} ({author_email}).<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>
'], 'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.rate.rate_toggle_status'], [
            'friendly_name' => 'Rating Status',
            'title' => 'Rating status has been changed',
            'extras' => [
                "bcc_users" => ["1"]
            ],
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello there,</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
Rating status for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has been changed for {author_name} ({author_email}) To {rating_status}.
<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>

<br/>
Thanks.
</p></td></tr></tbody></table>',
                'database' => 'Rating status 
 for "<b>{reviewrateable_identifier}</b>" ({reviewrateable_class}) has beed changed for {author_name} ({author_email}) To {rating_status}.<br/>
Check the following details:
<br/>
Rating value: {rating},<br/>
Rating title: {rating_title},<br/>
Rating Body: {rating_body},<br/>
Rating Criteria: {rating_criteria},<br/>
'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.comment.comment_created'], [
            'friendly_name' => 'New Comment Created',
            'title' => 'New Comment has been created',
            'extras' => [
                "bcc_users" => ["1"]
            ],
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello there,</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
New Comment for <a href="{commentable_show_url}" target="_blank"><b>{commentable_identifier}</b></a> ({commentable_class}) has been created by {author_name} ({author_email}).
<br/>
Check the following details:
<br/>
Comment Body: {comment_body},<br/>
Comment Status: {comment_status},
<br/>
<br/>
Thanks.
</p></td></tr></tbody></table>',
                'database' => 'New Comment for <a href="{commentable_show_url}" target="_blank"><b>{commentable_identifier}</b></a> ({commentable_class}) has been created by {author_name} ({author_email}).<br/>
Check the following details:
<br/>
Comment Body: {comment_body},<br/>
Comment Status: {comment_status},'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.comment.comment_toggle_status'], [
            'friendly_name' => 'Comment Status',
            'title' => 'Comment status has been changed',
            'extras' => [
                "bcc_users" => ["1"]
            ],
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%"><tbody><tr><td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px;">
<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Hello there,</p>
<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
<br/>
Comment status for <a href="{commentable_show_url}" target="_blank"><b>{commentable_identifier}</b></a> ({commentable_class}) has been changed for {author_name} ({author_email}).
<br/>
Check the following details:
<br/>
Comment Body: {comment_body},<br/>
Comment Status: {comment_status},
<br/>
<br/>
Thanks.
</p></td></tr></tbody></table>',
                'database' => 'Comment status for <a href="{commentable_show_url}" target="_blank"><b>{commentable_identifier}</b></a> ({commentable_class}) has been changed for {author_name} ({author_email}).<br/>
Check the following details:
<br/>
Comment Body: {comment_body},<br/>
Comment Status: {comment_status},'
            ],
            'via' => ["mail", "database"]
        ]);
    }
}
