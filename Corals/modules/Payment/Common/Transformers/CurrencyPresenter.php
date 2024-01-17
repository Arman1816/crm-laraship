<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class CurrencyPresenter extends FractalPresenter
{
    /**
     * @param array $extras
     * @return CurrencyTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new CurrencyTransformer($extras);
    }

}
