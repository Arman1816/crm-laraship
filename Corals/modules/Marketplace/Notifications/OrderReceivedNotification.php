<?php

namespace Corals\Modules\Marketplace\Notifications;

use Corals\Modules\Marketplace\Mails\OrderReceivedEmail;
use Corals\User\Communication\Classes\CoralsBaseNotification;
use Corals\User\Models\User;

class OrderReceivedNotification extends CoralsBaseNotification
{
    /**
     * @param null $subject
     * @param null $body
     * @return OrderReceivedEmail|null
     */
    protected function mailable($subject = null, $body = null)
    {
        $user = $this->data['user'] ?? new User();
        return new OrderReceivedEmail($user, $this->data['order'], $subject, $body);
    }

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $user = $this->data['user'];

        return $user->exists ? $user : [];
    }

    public function getOnDemandNotificationNotifiables()
    {
        $user = $this->data['user'];
        $order = $this->data['order'];

        if (!$user->exists) {
            return [
                'mail' => $order->billing['billing_address']['email']
            ];
        }
        return [];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $user = $this->data['user'];
        $order = $this->data['order'];

        return [
            'name' => $user->exists ? $user->name : $order->billing['billing_address']['first_name'],
            'my_orders_link' => url(config('marketplace.models.order.resource_url') . '/my'),
            'order_link' => url(config('marketplace.models.order.resource_url') . '/' . $this->data['order']->hashed_id)
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => 'Order user name',
            'my_orders_link' => 'User My Orders View Link',
            'order_link' => 'Order view link'
        ];
    }
}
