<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Utility\Http\Controllers\Rating\RatingBaseController;

class RatingController extends RatingBaseController
{
    protected function setCommonVariables()
    {
        $this->rateableClass = Product::class;
    }
}
