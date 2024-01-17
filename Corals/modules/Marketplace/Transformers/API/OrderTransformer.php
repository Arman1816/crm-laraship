<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Order;

class OrderTransformer extends APIBaseTransformer
{
    /**
     * @param Order $order
     * @return array
     * @throws \Throwable
     */
    public function transform(Order $order)
    {
        $payment_status = $order->billing['payment_status'] ?? '';

        if ($order->user) {
            $user = $order->user->full_name;
        } else {
            $user = trans('Marketplace::labels.order.guest');
        }

        $currency = strtoupper($order->currency);

        $transformedArray = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'amount' => \Payments::currency_convert($order->amount, null, $currency, true),
            'currency' => $currency,
            'payment_status' => $payment_status,
            'user' => $user,
            'shipping' => $order->shipping,
            'billing' => $order->billing,
            'properties' => $order->properties,
            'created_at' => format_date($order->created_at),
            'updated_at' => format_date($order->updated_at),
            'store_id' => $order->store_id,
            'store' => optional($order->store)->name,
        ];

        return parent::transformResponse($transformedArray);
    }
}
