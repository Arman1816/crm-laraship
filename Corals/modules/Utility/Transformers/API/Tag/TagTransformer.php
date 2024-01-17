<?php

namespace Corals\Modules\Utility\Transformers\API\Tag;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Utility\Models\Tag\Tag;

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
            'status' => $tag->status,
            'module' => $tag->module ?? '-',
            'created_at' => format_date($tag->created_at),
            'updated_at' => format_date($tag->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
