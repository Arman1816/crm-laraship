<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\OrderItem;
use Corals\Modules\Marketplace\Models\SKU;

class OrderItemTransformer extends BaseTransformer
{
    /**
     * @param OrderItem $order_item
     * @return array
     * @throws \Throwable
     */
    public function transform(OrderItem $order_item)
    {
        $order_item_description = $order_item->description;

        if ($order_item->type == "Product") {
            $sku = SKU::where('code', $order_item->sku_code)->first();
            if ($sku) {
                $order_item_description = $sku->product->name . ' (' . $sku->code . ')';
            }
        }

        $transformedArray = [
            'amount' => \Payments::currency_convert($order_item->amount, null, $order_item->order->currency, true),
            'description' => $order_item_description,
            'quantity' => $order_item->quantity,
            'sku_code' => $order_item->sku_code,
            'tax_ids' => $order_item->tax_ids,
            'type' => $order_item->type,
            'item_options' => $order_item->item_options,
            'properties' => $order_item->properties,
        ];

        return parent::transformResponse($transformedArray);
    }
}
