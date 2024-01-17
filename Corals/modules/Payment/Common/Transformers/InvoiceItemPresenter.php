<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class InvoiceItemPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return InvoiceItemTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new InvoiceItemTransformer($extras);
    }
}
