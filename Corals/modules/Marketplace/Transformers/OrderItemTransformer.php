<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\OrderItem;
use Corals\Modules\Marketplace\Models\SKU;

class OrderItemTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {

        parent::__construct($extras);
    }

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
                $order_item_description = '<a href="' . url('shop/' . $sku->product->slug) . '" target="_blank">' . $order_item_description . '</a>';
            }
        }

        $transformedArray = [
            'description' => $order_item_description,
        ];

        return parent::transformResponse($transformedArray);
    }
}
