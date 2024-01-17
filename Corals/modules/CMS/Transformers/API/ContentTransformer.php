<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\CMS\Facades\CMS;
use Corals\Modules\CMS\Models\Content;

class ContentTransformer extends APIBaseTransformer
{
    /**
     * @param Content $content
     * @return array
     * @throws \Throwable
     */
    public function transform(Content $content)
    {
        $featured_image = CMS::getContentFeaturedImage($content);
        $content = $content->toContentType();

        $transformedArray = [
            'id' => $content->id,
            'title' => $content->title,
            'content' => $content->content,
            'slug' => ($content->internal ? 'cms/' : '') . $content->slug,
            'published' => $content->published,
            'published_at' => $content->published ? format_date($content->published_at) : '-',
            'categories' => $content->categories ? $content->categories->pluck('name')->toArray() : [],
            'tags' => $content->tags ? $content->tags->pluck('name')->toArray() : [],
            'private' => $content->private,
            'internal' => $content->internal,
            'featured_image' => $featured_image,
            'created_at' => format_date($content->created_at),
            'updated_at' => format_date($content->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
