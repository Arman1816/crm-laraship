<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\CMS\Models\News;

class NewsTransformer extends APIBaseTransformer
{
    /**
     * @param News $news
     * @return array
     * @throws \Throwable
     */
    public function transform(News $news)
    {
        $transformedArray = [
            'id' => $news->id,
            'title' => $news->title,
            'slug' => ($news->internal ? 'cms/' : '') . $news->slug,
            'published' => $news->published,
            'published_at' => $news->published ? format_date($news->published_at) : '-',
            'private' => $news->private,
            'internal' => $news->internal,
            'created_at' => format_date($news->created_at),
            'updated_at' => format_date($news->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
