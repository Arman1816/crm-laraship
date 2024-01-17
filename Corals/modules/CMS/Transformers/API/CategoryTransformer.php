<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\CMS\Models\Category;

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
            'slug' => $category->slug,
            'posts_count' => $category->posts_count,
            'belongs_to' => $category->belongs_to,
            'status' => $category->status,
            'created_at' => format_date($category->created_at),
            'updated_at' => format_date($category->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
