<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class InvoicePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return InvoiceTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new InvoiceTransformer($extras);
    }
}
