<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TransactionPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return TransactionTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new TransactionTransformer($extras);
    }
}
