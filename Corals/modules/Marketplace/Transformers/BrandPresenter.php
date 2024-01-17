<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class BrandPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return BrandTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new BrandTransformer($extras);
    }
}
