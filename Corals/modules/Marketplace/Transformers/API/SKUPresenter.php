<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class SKUPresenter extends FractalPresenter
{

    /**
     * @return SKUTransformer
     */
    public function getTransformer()
    {
        return new SKUTransformer();
    }
}
