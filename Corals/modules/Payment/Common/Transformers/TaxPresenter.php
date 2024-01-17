<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TaxPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return TaxTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new TaxTransformer($extras);
    }
}
