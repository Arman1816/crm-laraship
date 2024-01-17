<?php

namespace Corals\Modules\Utility\Classes\Rating;


use Corals\Foundation\Search\Indexable;
use Corals\Modules\Utility\Models\Rating\AvgRating;
use Corals\Modules\Utility\Models\Rating\Rating as RatingModel;
use Corals\Modules\Utility\Traits\Rating\ReviewRateable;
use Illuminate\Database\Eloquent\Model;

class RatingManager
{

    protected $instance, $author;

    /**
     * RatingManager constructor.
     * @param $instance
     * @param $author
     */
    public function __construct($instance = null, $author = null)
    {
        $this->instance = $instance;
        $this->author = $author;
    }

    /**
     * @param $data
     * @return RatingModel|Model
     */
    public function createRating($data)
    {
        $data = array_merge([
            'reviewrateable_id' => $this->instance->id,
            'reviewrateable_type' => getMorphAlias($this->instance),
            'author_id' => $this->author->id,
            'author_type' => getMorphAlias($this->author),
        ], $data);

        $ratingModel = RatingModel::create($data);

        event('notifications.rate.rate_created', [
            'rating' => $ratingModel,
        ]);

        return $ratingModel;
    }

    /**
     * @param RatingModel $rating
     * @param $data
     * @return bool
     */
    public function updateRating(RatingModel $rating, $data)
    {
        $rating->update($data);
    }

    /**
     * @param $rating
     * @param $isNewReview
     * @param null $oldRateValue
     * @param false $toggleStatus
     * @return bool|\Illuminate\Database\Eloquent\Builder|Model|int|mixed|null
     * @throws \Exception
     */
    protected function storeUpdateAvgRating($rating, $isNewReview, $oldRateValue = null, $toggleStatus = false)
    {
        if ($rating->criteria) {
            return $this->storeUpdateAvgRatingCriteria($rating, $isNewReview, $oldRateValue, $toggleStatus);
        }

        $rateable = $rating->ratable;

        if ($parent_obj = $rateable->AggregatedRatingParentModel()) {
            $parent_rating = clone $rating;
            $parent_rating->reviewrateable_id = $parent_obj->id;
            $parent_rating->reviewrateable_type = getMorphAlias(get_class($parent_obj));
            $this->storeUpdateGeneralAvgRating($parent_rating, $isNewReview, $oldRateValue, $toggleStatus);
        }


        return $this->storeUpdateGeneralAvgRating($rating, $isNewReview, $oldRateValue, $toggleStatus);

    }

    /**
     * @param $rating
     * @param $isNewReview
     * @param $oldRateValue
     * @param $toggleStatus
     * @return bool|\Illuminate\Database\Eloquent\Builder|Model|int|mixed|null
     * @throws \Exception
     */
    protected function storeUpdateAvgRatingCriteria($rating, $isNewReview, $oldRateValue, $toggleStatus)
    {
        $avgRating = AvgRating::query()
            ->firstOrCreate([
                'avgreviewable_type' => $rating->reviewrateable_type,
                'avgreviewable_id' => $rating->reviewrateable_id
            ], [
                'avg' => $rating->rating,
                'count' => 1,
                'criterias' => [
                    $rating->criteria => [
                        'avg' => $rating->rating,
                        'count' => 1
                    ]
                ]
            ]);


        if ($avgRating->wasRecentlyCreated) {
            return $avgRating;
        }

        $criterias = $avgRating->criterias;

        $criteria = $criterias[$rating->criteria] ?? ['avg' => $rating->rating, 'count' => 0];


        if ($isNewReview || ($rating->status == 'approved' && $toggleStatus)) {

            if ($criteria['count'] == 0) {
                $criteria['count'] = 1;
                $newReviewsCount = 1;
                $avg = $criteria['avg'];
            } else {
                $newReviewsCount = $criteria['count'] + 1;
                $avg = (($criteria['avg'] * $criteria['count']) + $rating->rating) / $newReviewsCount;
            }
        } else {

            if ($rating->status == 'approved') {
                $avg = (($criteria['avg'] * $criteria['count']) + ($rating->rating - $oldRateValue)) / $criteria['count'];
            } else {
                $newReviewsCount = $criteria['count'] - 1;

                if ($newReviewsCount == 0) {
                    $avg = 0;
                } else {
                    $avg = (($criteria['avg'] * $criteria['count']) - $rating->rating) / $newReviewsCount;
                }

            }


        }

        $criterias[$rating->criteria] = [
            'avg' => $avg,
            'count' => $newReviewsCount ?? $criteria['count']
        ];


        $generalAvg = 0;
        $generalCount = 0;

        foreach ($criterias as $criteria) {

            $generalAvg += $criteria['avg'];
            $generalCount += $criteria['count'];

        }

        $generalAvg = $generalAvg / count($criterias);

        return $generalAvg == 0 ? $avgRating->delete() : tap($avgRating)->update([
            'count' => $generalCount,
            'avg' => $generalAvg,
            'criterias' => $criterias
        ]);


    }

    /**
     * @param $rating
     * @param $isNewReview
     * @param $oldRateValue
     * @param $toggleStatus
     * @return bool|\Illuminate\Database\Eloquent\Builder|Model|int|mixed|null
     * @throws \Exception
     */
    protected function storeUpdateGeneralAvgRating($rating, $isNewReview, $oldRateValue, $toggleStatus)
    {
        $avgRating = AvgRating::query()
            ->firstOrCreate([
                'avgreviewable_type' => $rating->reviewrateable_type,
                'avgreviewable_id' => $rating->reviewrateable_id
            ], [
                'avg' => $rating->rating,
                'count' => 1,
            ]);


        if ($avgRating->wasRecentlyCreated) {
            return $avgRating;
        }


        if ($isNewReview || ($rating->status == 'approved' && $toggleStatus)) {
            $newReviewsCount = $avgRating->count + 1;
            $avg = (($avgRating->avg * $avgRating->count) + $rating->rating) / $newReviewsCount;
        } else {
            //in case of update review!

            if ($rating->status == 'approved') {
                $avg = (($avgRating->avg * $avgRating->count) + ($rating->rating - $oldRateValue)) / $avgRating->count;

            } else {

                $newReviewsCount = $avgRating->count - 1;

                if ($newReviewsCount == 0) {
                    $avg = 0;
                } else {

                    $avg = (($avgRating->avg * $avgRating->count) - $rating->rating) / $newReviewsCount;
                }

            }

        }


        return $avg == 0 ? $avgRating->delete() : tap($avgRating)->update([
            'count' => $newReviewsCount ?? $avgRating->count,
            'avg' => $avg
        ]);
    }

    public function handleModelRating($data)
    {
        $rating = $this->instance->ratings()->where([
            'author_id' => $this->author->id,
            'author_type' => getMorphAlias($this->author),
            'criteria' => $data['criteria'] ?? null,
        ])->first();


        if ($rating) {
            $oldRating = $rating->rating;
            $this->updateRating($rating, $data);

        } else {
            $setting_name = strtolower(class_basename($this->instance)) . '_default_rating_status';
            $data['status'] = \Settings::get($setting_name, 'approved');
            $newReview = true;

            $rating = $this->createRating($data);
        }

        if ($rating->status == 'approved') {
            $this->storeUpdateAvgRating($rating, $newReview ?? false, $oldRating ?? null);
        }

        return $rating;
    }

    /**
     * @param RatingModel $rating
     * @return bool|null
     * @throws \Exception
     */
    public function deleteRating($rating)
    {
        return $rating->delete();
    }

    public function getModelAvgRating($model)
    {
        return $rating->delete();
    }

    public function toggleStatus($rating, $status)
    {
        $update = $rating->update([
            'status' => $status,
        ]);

        event('notifications.rate.rate_toggle_status', [
            'rating' => $rating,
        ]);

        $this->storeUpdateAvgRating($rating, false, 0, true);

        return $update;

    }

    public function drawStarts($count = 0)
    {

        $stars = '';

        for ($i = 1; $i <= 5; $i++) {
            $muted = $count >= $i ? "" : "-o";
            $stars .= '<i class="fa fa-star' . $muted . '"></i>';
        }

        return $stars;
    }

    public function CalculateAvgByClass($class)
    {

        $model = new $class;

        if (in_array(ReviewRateable::class, class_uses($model), true)) {

            $model->chunk(100, function ($chunk) use ($model) {
                foreach ($chunk as $modelRecord) {
                    $model_reviews = $modelRecord->ratings('approved');

                    $reviews_count = $model_reviews->count();

                    if ($reviews_count) {
                        $review_sum = $model_reviews->sum('rating');
                        $avg_rating = $review_sum / $reviews_count;
                        AvgRating::query()
                            ->firstOrCreate([
                                'avgreviewable_type' => getMorphAlias($model),
                                'avgreviewable_id' => $modelRecord->id
                            ], [
                                'avg' => $avg_rating,
                                'count' => $reviews_count,
                            ]);

                        if ($parent_obj = $modelRecord->AggregatedRatingParentModel()) {
                            foreach ($modelRecord->ratings as $review){

                                $parent_rating = clone $review;
                                $parent_rating->reviewrateable_id = $parent_obj->id;
                                $parent_rating->reviewrateable_type = getMorphAlias(get_class($parent_obj));
                                $this->storeUpdateGeneralAvgRating($parent_rating, true, null, false);
                            }

                        }
                    }


                }
            });
        }
    }
}
