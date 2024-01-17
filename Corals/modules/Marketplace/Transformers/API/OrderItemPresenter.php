<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class OrderItemPresenter extends FractalPresenter
{

    /**
     * @return OrderItemTransformer
     */
    public function getTransformer()
    {
        return new OrderItemTransformer();
    }
}
