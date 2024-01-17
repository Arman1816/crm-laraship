<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class MarketplaceNotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.order.received'], [
            'friendly_name' => 'New Order Received',
            'title' => 'Thank You For Your Order!',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;"> <tr> <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Thank You For Your Order! </h2> </td></tr><tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;"> <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium iste ipsa numquam odio dolores, nam. </p></td></tr><tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <h5 style="font-size: 15px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Order Details </h5> </td></tr></table>',
                'database' => '<p>Thank You For Your Order! check your orders <a href="{my_orders_link}">Here</a></p>'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.store_order.received'], [
            'friendly_name' => 'You Received an order at your store',
            'title' => 'An order has been added to your store',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;"> <tr> <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Thank You For Your Order! </h2> </td></tr><tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;"> <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium iste ipsa numquam odio dolores, nam. </p></td></tr><tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <h5 style="font-size: 15px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Order Details </h5> </td></tr></table>',
                'database' => '<p>You receieved an order at your store <a href="{store_orders_link}">Here</a></p>'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.order.updated'], [
            'friendly_name' => 'Order Updated',
            'title' => 'Order has been updated by Seller',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;"> <tr> <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Thank You For Your Order! </h2> </td></tr><tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;"> <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium iste ipsa numquam odio dolores, nam. </p></td></tr><tr> <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <h5 style="font-size: 15px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Order Details </h5> </td></tr></table>',
                'database' => '<p>Your Order#{order_number} has been updated, check it out <a href="{order_link}">Here</a></p>'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(
            ['name' => 'notifications.marketplace.withdrawal.requested'], [
            'friendly_name' => 'Withdrawal  has been requested',
            'title' => 'A Withdrawal has been requested ',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                            <tr>
                                <td align="center"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                    <h4 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                        {user_name} has requested withdrawal of his balance </h4></td>
                            </tr>
                            <tr>
                                <td align="left"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                    <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Transaction Amount :
                                        {transaction_amount}
                                        with transaction notes: {transaction_notes} </p></td>
                            </tr>
                            <tr>
                                <td align="left"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                    <h5 style="font-size: 15px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Click <a
                                            target="_blank" href="{transaction_url}">here</a> to view transaction </h5></td>
                            </tr>
                        </table>',
                'database' => '<p>A transaction has been requested by {user_name}  Click <a target="_blank" href="{transaction_url}">Here</a> to view transaction</p>'
            ],
            'via' => ["mail", "database"],
            'extras' => ['bcc_roles' => [1]]

        ]);


        NotificationTemplate::updateOrCreate(
            ['name' => 'notifications.marketplace.withdrawal_request.updated'], [
            'friendly_name' => 'Your Withdrawal Requested  has been updated',
            'title' => 'A Withdrawal has been requested ',
            'body' => [
                'mail' => '<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                            <tr>
                                <td align="center"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                    Hello {user_name}</td>
                            </tr>
                            <tr>
                                <td align="left"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                    <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;"> Your withdrawal request has been updated </p></td>
                            </tr>
                            <tr>
                                <td align="left"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                    <h5 style="font-size: 15px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Click <a
                                            target="_blank" href="{transaction_url}">here</a> to view transaction </h5></td>
                            </tr>
                        </table>',
                'database' => '<p>Your withdrawal transaction has been updated, click <a target="_blank" href="{transaction_url}">Here</a> to view transaction</p>'
            ],
            'via' => ["mail", "database"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.abandoned_cart'], [
            'friendly_name' => 'Marketplace Abandoned Cart',
            'title' => 'Check what you missed here!!',
            'body' => [
                'mail' => '<p>Hi&nbsp;<b id="shortcode_name">{name},</b></p><p><b>check what you missed,</b></p><p><a href="{abandoned_cart_link}" target="_blank"><b>Open your missed cart</b></a></p>',
            ],
            'via' => ["mail"]
        ]);

        NotificationTemplate::updateOrCreate(['name' => 'notifications.marketplace.import_status'], [
            'friendly_name' => 'Marketplace Products Import Status',
            'title' => 'Marketplace {import_file_name} Import Status',
            'body' => [
                'mail' => '<p>Hi {user_name},</p><p>Here you are the {import_file_name} Import Status:</p><p>Success Imported Records: <b>{success_records_count}</b></p>
<p style="color:red;">Failed Imported Records: <b>{failed_records_count}</b><br/>{import_log_file}</p>',
            ],
            'via' => ["mail"]
        ]);
    }
}
