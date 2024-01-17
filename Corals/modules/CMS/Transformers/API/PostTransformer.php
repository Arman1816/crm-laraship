<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\CMS\Models\Post;

class PostTransformer extends APIBaseTransformer
{
    /**
     * @param Post $post
     * @return array
     * @throws \Throwable
     */
    public function transform(Post $post)
    {
        $transformedArray = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => ($post->internal ? 'cms/' : '') . $post->slug,
            'published' => $post->published,
            'published_at' => $post->published ? format_date($post->published_at) : '-',
            'categories' => $post->categories->pluck('name')->toArray(),
            'private' => $post->private,
            'internal' => $post->internal,
            'created_at' => format_date($post->created_at),
            'updated_at' => format_date($post->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
