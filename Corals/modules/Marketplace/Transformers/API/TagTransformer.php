<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Tag;

class TagTransformer extends APIBaseTransformer
{
    /**
     * @param Tag $tag
     * @return array
     * @throws \Throwable
     */
    public function transform(Tag $tag)
    {
        $transformedArray = [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'products_count' => $tag->products_count,
            'status' => $tag->status,
            'created_at' => format_date($tag->created_at),
            'updated_at' => format_date($tag->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
