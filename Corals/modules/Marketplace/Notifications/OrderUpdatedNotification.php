<?php

namespace Corals\Modules\Marketplace\Notifications;

use Corals\Modules\Marketplace\Mails\OrderUpdatedEmail;
use Corals\User\Communication\Classes\CoralsBaseNotification;

class OrderUpdatedNotification extends CoralsBaseNotification
{
    /**
     * @param null $subject
     * @param null $body
     * @return OrderUpdatedEmail|null
     */
    protected function mailable($subject = null, $body = null)
    {
        return new OrderUpdatedEmail($this->data['order'], $subject, $body);
    }

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $order_buyer = $this->data['order']->user;
        if ($order_buyer) {
            return [$order_buyer];
        } else {
            return [];
        }
    }

    public function getOnDemandNotificationNotifiables()
    {
        $order = $this->data['order'];
        $order_buyer = $order->user;
        if (!$order_buyer) {
            return [
                'mail' => $order->billing['billing_address']['email']
            ];
        }

        return [];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $order = $this->data['order'];
        $user = $order->user;

        return [
            'name' => $user ? $user->full_name : $order->billing['billing_address']['first_name'],
            'order_link' => url(config('marketplace.models.order.resource_url') . '/' . $order->hashed_id),
            'order_number' => $order->order_number
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => 'Order user name',
            'order_number' => 'order Number',
            'order_link' => 'Order view link'
        ];
    }
}
