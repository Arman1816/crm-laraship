<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class OrderItemPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return OrderItemTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new OrderItemTransformer($extras);
    }
}
