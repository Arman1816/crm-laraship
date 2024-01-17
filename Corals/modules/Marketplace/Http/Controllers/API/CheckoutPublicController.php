<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIPublicController;
use Corals\Modules\Marketplace\Services\CheckoutService;
use Corals\Modules\Marketplace\Traits\API\CheckoutControllerCommonFunctions;

class CheckoutPublicController extends APIPublicController
{
    use CheckoutControllerCommonFunctions;

    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;

        parent::__construct();
    }
}
