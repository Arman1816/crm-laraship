<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class AttributeSetPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return AttributeSetTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new AttributeSetTransformer($extras);
    }
}
