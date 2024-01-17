<?php

namespace Corals\Modules\Utility\Transformers\API\Rating;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Utility\Models\Rating\Rating;
use Corals\Modules\Utility\Transformers\API\Comment\CommentPresenter;

class RatingTransformer extends APIBaseTransformer
{
    /**
     * @param Rating $rating
     * @return array
     * @throws \Exception
     */
    public function transform(Rating $rating)
    {
        $transformedArray = [
            'id' => $rating->id,
            'rating' => $rating->rating,
            'title' => $rating->title,
            'body' => $rating->body,
            'reviewrateable_id' => $rating->reviewrateable_id,
            'reviewrateable_type' => $rating->reviewrateable ? class_basename($rating->reviewrateable_type) : null,
            'author_id' => $rating->author_id,
            'author' => $rating->author ? $rating->author->full_name : null,
            'status' => $rating->status,
            'comments' => (new CommentPresenter())->present($rating->comments)['data'],
            'created_at' => format_date($rating->created_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
