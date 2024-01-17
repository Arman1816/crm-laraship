<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Utility\Http\Controllers\API\Rating\RatingAPIBaseController;

class RatingController extends RatingAPIBaseController
{
    protected function setCommonVariables()
    {
        $this->rateableClass = Product::class;
    }
}
