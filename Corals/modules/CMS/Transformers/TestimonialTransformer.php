<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\CMS\Models\Post;
use Corals\Modules\CMS\Models\Testimonial;

class TestimonialTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('cms.models.testimonial.resource_url');
        parent::__construct($extras);
    }

    /**
     * @param Testimonial $testimonial
     * @return array
     * @throws \Throwable
     */

    public function transform(Testimonial $testimonial)
    {

        $transformedArray = [
            'id' => $testimonial->id,
            'image' => '<img src="' . $testimonial->image . '" class="img-responsive" alt="User Picture" width="45"/>',
            'title' => \Str::limit($testimonial->title, 50),
            'job_title' => $testimonial->getProperty('job_title'),
            'rating' => \RatingManager::drawStarts($testimonial->getProperty('rating')),
            'published' => $testimonial->published ? '<i class="fa fa-check text-success"></i>' : '-',
            'published_at' => $testimonial->published ? format_date($testimonial->published_at) : '-',
            'created_at' => format_date($testimonial->created_at),
            'updated_at' => format_date($testimonial->updated_at),
            'action' => $this->actions($testimonial)
        ];

        return parent::transformResponse($transformedArray);
    }
}
