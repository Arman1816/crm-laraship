<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class CouponPresenter extends FractalPresenter
{

    /**
     * @return CouponTransformer
     */
    public function getTransformer()
    {
        return new CouponTransformer();
    }
}
