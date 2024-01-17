<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class CouponPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return CouponTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new CouponTransformer($extras);
    }
}
