<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class StorePresenter extends FractalPresenter
{

    /**
     * @return StoreTransformer
     */
    public function getTransformer()
    {
        return new StoreTransformer();
    }
}
