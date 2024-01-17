<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ShippingPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ShippingTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ShippingTransformer($extras);
    }
}
