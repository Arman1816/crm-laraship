<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\CMS\Models\Faq;

class FaqTransformer extends APIBaseTransformer
{
    /**
     * @param Faq $faq
     * @return array
     * @throws \Throwable
     */
    public function transform(Faq $faq)
    {
        $transformedArray = [
            'id' => $faq->id,
            'title' => $faq->title,
            'published' => $faq->published,
            'published_at' => $faq->published ? format_date($faq->published_at) : '-',
            'categories' => $faq->categories->pluck('name')->toArray(),
            'created_at' => format_date($faq->created_at),
            'updated_at' => format_date($faq->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
