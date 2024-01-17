<?php

namespace Corals\Modules\Marketplace\Notifications;

use Corals\Modules\Marketplace\Mails\OrderReceivedEmail;
use Corals\User\Communication\Classes\CoralsBaseNotification;

class StoreOrderReceivedNotification extends CoralsBaseNotification
{
    /**
     * @param null $subject
     * @param null $body
     * @return OrderReceivedEmail|null
     */
    protected function mailable($subject = null, $body = null)
    {
        return new OrderReceivedEmail($this->data['user'], $this->data['order'], $subject, $body,
            ['storeEmail' => true]);
    }

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $storeOwner = $this->data['order']->store->user;
        return [$storeOwner];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {

        return [
            'store_orders_link' => url(config('marketplace.models.order.resource_url') . '/store'),
            'order_link' => url(config('marketplace.models.order.resource_url') . '/' . $this->data['order']->hashed_id)
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'store_orders_link' => 'Store Orders View Link',
            'order_link' => 'Order view link'
        ];
    }
}
