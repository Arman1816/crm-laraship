<?php

namespace Corals\Modules\Subscriptions\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class SubscriptionRenewalInvoiceNotification extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $subscription = $this->data['subscription'];

        return $subscription->user;
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $subscription = $this->data['subscription'];

        $invoice = $subscription->invoice;

        $user = $subscription->user;

        return [
            'user' => $user->full_name,
            'dashboard_link' => url('dashboard'),
            'reference' => $subscription->subscription_reference,
            'created_at' => format_date($subscription->created_at),
            'ends_at' => format_date($subscription->ends_at),
            'plan_name' => $subscription->plan->name,
            'plan_price' => \Payments::currency($subscription->plan->price),
            'plan_frequency' => $subscription->plan->bill_frequency,
            'plan_cycle' => $subscription->plan->bill_cycle,
            'product_name' => $subscription->plan->product->name,
            'remaining_days' => $subscription->remainingDays(),
            'invoice_public_link' => $invoice ? $invoice->present('public_link') : '-',
            'gatewayPaymentDetails' => $invoice ? $invoice->getInvoicePaymentDetails() : '',
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'user' => 'Subscription user name',
            'dashboard_link' => 'User dashboard Link',
            'reference' => 'Subscription reference',
            'created_at' => 'Subscription created at',
            'ends_at' => 'Subscription ends at',
            'plan_name' => 'Plan name',
            'plan_price' => 'Plan price',
            'plan_frequency' => 'Plan bill frequency',
            'plan_cycle' => 'Plan bill cycle',
            'product_name' => 'Plan product name',
            'invoice_public_link' => "Public Invoice Link",
            'gatewayPaymentDetails' => 'Gateway Payment Details',
        ];
    }
}
