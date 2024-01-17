<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Brand;

class BrandTransformer extends APIBaseTransformer
{
    /**
     * @param Brand $brand
     * @return array
     * @throws \Throwable
     */
    public function transform(Brand $brand)
    {

        $transformedArray = [
            'id' => $brand->id,
            'name' => $brand->name,
            'slug' => $brand->slug,
            'thumbnail' => $brand->thumbnail,
            'products_count' => $brand->products_count,
            'status' => $brand->status,
            'is_featured' => $brand->is_featured,
            'created_at' => format_date($brand->created_at),
            'updated_at' => format_date($brand->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
