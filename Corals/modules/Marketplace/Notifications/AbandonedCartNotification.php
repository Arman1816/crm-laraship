<?php

namespace Corals\Modules\Marketplace\Notifications;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class AbandonedCartNotification extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return [];
    }

    public function getOnDemandNotificationNotifiables()
    {
        $cart = $this->data['cart'];

        return [
            'mail' => $cart->email
        ];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $cart = $this->data['cart'];
        $user = $cart->user;

        return [
            'name' => $user->name ?? '',
            'abandoned_cart_link' => url('cart/load/' . encrypt($cart->email)),
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'name' => 'User name',
            'abandoned_cart_link' => 'Abandoned cart link',
        ];
    }
}
