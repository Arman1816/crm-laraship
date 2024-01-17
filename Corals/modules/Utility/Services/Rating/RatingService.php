<?php

namespace Corals\Modules\Utility\Services\Rating;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Utility\Classes\Rating\RatingManager;

class RatingService extends BaseServiceClass
{
    public function createRating($request, $rateableClass, $rateable_hashed_id)
    {
        if (is_null($rateableClass)) {
            abort(400, 'Rating class is null');
        }

        $rateable = $rateableClass::findByHash($rateable_hashed_id);

        if (!$rateable) {
            abort(404, 'Not Found!!');
        }

        $data = $request->all();

        $ratingClass = new RatingManager($rateable, user());

        $rating = $ratingClass->handleModelRating([
            'rating' => $data['review_rating'],
            'title' => $data['review_subject'] ?? null,
            'body' => $data['review_text'] ?? null,
            'criteria' => $data['criteria'] ?? null,
        ]);

        return $rating;
    }
}
