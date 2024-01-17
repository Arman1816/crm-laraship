<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\SKU;

class SKUTransformer extends APIBaseTransformer
{

    /**
     * @param SKU $sku
     * @return array
     * @throws \Throwable
     */
    public function transform(SKU $sku)
    {
        $sku_attributes = [];

        if ($sku->product->type == "variable") {
            $options = $sku->options ?? [];

            foreach ($options as $option) {
                $sku_attributes[] = [
                    'id' => $option->attribute_id,
                    'label' => $option->attribute->label,
                    'formatted_value' => $option->formatted_value,
                ];
            }
        }

        $transformedArray = [
            'id' => $sku->id,
            'code' => $sku->code ?? null,
            'image' => $sku->image,
            'price' => floatValWithLeftMost($sku->price),
            'formatted_price' => \Payments::currency($sku->price),
            'inventory' => $sku->inventory,
            'inventory_value' => $sku->inventory_value,
            'attributes' => $sku_attributes,
            'status' => $sku->status,
            'created_at' => format_date($sku->created_at),
            'updated_at' => format_date($sku->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
