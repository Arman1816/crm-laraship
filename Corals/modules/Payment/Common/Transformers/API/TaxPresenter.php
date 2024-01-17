<?php

namespace Corals\Modules\Payment\Common\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class TaxPresenter extends FractalPresenter
{

    /**
     * @return TaxTransformer
     */
    public function getTransformer()
    {
        return new TaxTransformer();
    }
}
