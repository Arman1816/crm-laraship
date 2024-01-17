<?php

namespace Corals\Modules\Utility\Transformers\API\Category;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Utility\Models\Category\Category;

class CategoryTransformer extends APIBaseTransformer
{
    /**
     * @param Category $category
     * @return array
     * @throws \Exception
     */
    public function transform(Category $category)
    {
        $attributes = $category->categoryAttributes;

        $transformedArray = [
            'id' => $category->id,
            'name' => $category->name,
            'is_featured' => $category->is_featured,
            'slug' => $category->slug,
            'parent_id' => optional($category->parent)->name ?? '-',
            'status' => $category->status,
            'created_at' => format_date($category->created_at),
            'updated_at' => format_date($category->updated_at),
            'attributes' => (new AttributePresenter())->present($attributes)['data'],
        ];

        return parent::transformResponse($transformedArray);
    }
}
