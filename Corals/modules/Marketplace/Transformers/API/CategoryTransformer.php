<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Category;

class CategoryTransformer extends APIBaseTransformer
{
    /**
     * @param Category $category
     * @return array
     * @throws \Throwable
     */
    public function transform(Category $category)
    {
        $transformedArray = [
            'id' => $category->id,
            'name' => $category->name,
            'is_featured' => $category->is_featured,
            'slug' => $category->slug,
            'products_count' => $category->products_count,
            'parent' => optional($category->parent)->name,
            'status' => $category->status,
            'thumbnail' => $category->thumbnail,
            'created_at' => format_date($category->created_at),
            'updated_at' => format_date($category->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
