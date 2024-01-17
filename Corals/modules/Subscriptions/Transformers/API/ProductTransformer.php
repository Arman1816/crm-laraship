<?php

namespace Corals\Modules\Subscriptions\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Subscriptions\Models\Product;

class ProductTransformer extends APIBaseTransformer
{
    /**
     * @param Product $product
     * @return array
     * @throws \Exception
     */
    public function transform(Product $product)
    {
        $plans = (new PlanPresenter())->present($product->activePlans)['data'];

        $transformedArray = [
            'id' => $product->id,
            'image' => $product->image,
            'name' => $product->name,
            'status' => $product->status,
            'description' => $product->description,
            'created_at' => format_date($product->created_at),
            'updated_at' => format_date($product->updated_at),
            'plans' => $plans,
        ];

        return parent::transformResponse($transformedArray);
    }
}
