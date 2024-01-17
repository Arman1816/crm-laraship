<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\CMS\Models\Page;

class PageTransformer extends APIBaseTransformer
{
    /**
     * @param Page $page
     * @return array
     * @throws \Throwable
     */
    public function transform(Page $page)
    {
        $transformedArray = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => ($page->internal ? 'cms/' : '') . $page->slug,
            'published' => $page->published,
            'published_at' => $page->published ? format_date($page->published_at) : '-',
            'private' => $page->private,
            'internal' => $page->internal,
            'created_at' => format_date($page->created_at),
            'updated_at' => format_date($page->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
