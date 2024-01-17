<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Subscriptions\Models\Product;

class ProductTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('subscriptions.models.product.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Product $product
     * @return array
     * @throws \Throwable
     */
    public function transform(Product $product)
    {

        $transformedArray = [
            'id' => $product->id,
            'image' => '<a href="#">' . '<img src="' . $product->image . '" class=" img-responsive" alt="Product Image" width="50"/></a>',
            'name' => $product->name,
            'status' => formatStatusAsLabels($product->status),
            'description' => generatePopover($product->description),
            'created_at' => format_date($product->created_at),
            'updated_at' => format_date($product->updated_at),
            'short_code' => "@pricing({$product->id})",
            'action' => $this->actions($product)
        ];

        return parent::transformResponse($transformedArray);
    }
}
