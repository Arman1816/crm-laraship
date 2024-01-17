<?php

namespace Corals\Modules\Utility\Traits\Rating;


trait RatingCommon
{
    protected $ratingService;
    protected $rateableClass = null;
    protected $redirectUrl = null;
    protected $successMessage = 'Utility::messages.rating.success.add';
    protected $successMessageWithPending = 'Utility::messages.rating.success.add_with_pending';


    protected function setCommonVariables()
    {
        $this->rateableClass = null;
        $this->redirectUrl = null;
    }
}
