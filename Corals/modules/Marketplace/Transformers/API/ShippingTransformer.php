<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Shipping;

class ShippingTransformer extends APIBaseTransformer
{
    /**
     * @param Shipping $shipping
     * @return array
     */
    public function transform(Shipping $shipping)
    {
        $transformedArray = [
            'id' => $shipping->id,
            'priority' => $shipping->priority,
            'exclusive' => $shipping->exclusive,
            'name' => $shipping->name,
            'shipping_method' => $shipping->shipping_method,
            'store_id' => $shipping->store_id,
            'store' => optional($shipping->store)->name,
            'rate' => $shipping->rate ? \Currency::format($shipping->rate, \Payments::admin_currency_code()) : null,
            'min_order_total' => $shipping->min_order_total ? \Currency::format($shipping->min_order_total, \Payments::admin_currency_code()) : null,
            'country' => $shipping->country ?? 'All Countries',
            'description' => $shipping->description,
        ];

        return parent::transformResponse($transformedArray);
    }
}
