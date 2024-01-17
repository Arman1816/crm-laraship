<?php

namespace Corals\Modules\Utility\Http\Controllers\API\Rating;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Utility\DataTables\Rating\RatingsDataTable;
use Corals\Modules\Utility\Http\Requests\Rating\RatingRequest;
use Corals\Modules\Utility\Models\Rating\Rating;
use Corals\Modules\Utility\Services\Rating\RatingService;
use Corals\Modules\Utility\Traits\Rating\RatingCommon;

class RatingAPIBaseController extends APIBaseController
{
    use RatingCommon;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;

        $this->setCommonVariables();

        parent::__construct();
    }

    /**
     * @param RatingRequest $request
     * @param RatingsDataTable $dataTable
     * @return mixed
     */
    public function index(RatingRequest $request, RatingsDataTable $dataTable)
    {
        $ratings = $dataTable->query(new Rating());

        return $this->ratingService->index($ratings, $dataTable);
    }

    public function createRating(RatingRequest $request, $rateable_hashed_id)
    {
        try {
            $rating = $this->ratingService->createRating($request, $this->rateableClass, $rateable_hashed_id);

            if ($rating->status == 'pending') {
                $message = $this->successMessageWithPending;
            } else {
                $message = $this->successMessage;
            }

            return apiResponse(['status' => $rating->status], trans($message));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
