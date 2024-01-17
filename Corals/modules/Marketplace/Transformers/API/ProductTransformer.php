<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Product;

class ProductTransformer extends APIBaseTransformer
{
    /**
     * @param Product $product
     * @return array
     * @throws \Throwable
     */
    public function transform(Product $product)
    {
        $sku = null;

        if ($product->type == "simple") {
            $sku = (new SKUPresenter())->present($product->activeSKU(true))['data'];
        }

        $gallery = [];

        foreach ($product->getMedia($product->galleryMediaCollection) as $media) {
            $gallery[] = [
                'id' => $media->id,
                'url' => $media->getFullUrl()
            ];
        }

        $transformedArray = [
            'id' => $product->id,
            'image' => $product->image,
            'name' => $product->name,
            'slug' => $product->slug,
            'is_featured' => $product->is_featured,
            'external_url' => $product->external_url,
            'price' => floatValWithLeftMost(strip_tags($product->price)),
            'formatted_price' => strip_tags($product->price),
            'regular_price' => $product->regular_price,
            'discount' => $product->discount,
            'type' => $product->type,
            'is_simple' => $product->type == "simple",
            'brand' => $product->brand ? $product->brand->name : null,
            'caption' => $product->caption,
            'shippable' => boolval($product->shipping['enabled']),
            'status' => $product->status,
            'categories' => $product->activeCategories->pluck('name')->toArray(),
            'tags' => $product->activeTags->pluck('name')->toArray(),
            'description' => $product->description,
            'variation_attributes' => apiPluck($product->variationOptions->pluck('label', 'id'), 'id', 'label'),
            'tax_classes' => apiPluck($product->tax_classes()->pluck('tax_classes.name', 'tax_classes.id'), 'id', 'name'),
            'sku' => $sku,
            'system_price' => $product->system_price,
            'store_id' => $product->store_id,
            'store' => optional($product->store)->name,
            'gateway_status' => $product->getGatewayStatus(),
            'gallery' => $gallery,
            'created_at' => format_date($product->created_at),
            'updated_at' => format_date($product->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
