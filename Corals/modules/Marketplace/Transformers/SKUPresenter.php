<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class SKUPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SKUTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SKUTransformer($extras);
    }
}
