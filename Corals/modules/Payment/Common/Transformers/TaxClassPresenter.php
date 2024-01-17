<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TaxClassPresenter extends FractalPresenter
{

    /**
     * @return TaxClassTransformer
     */
    public function getTransformer($extras = [])
    {
        return new TaxClassTransformer($extras);
    }
}
