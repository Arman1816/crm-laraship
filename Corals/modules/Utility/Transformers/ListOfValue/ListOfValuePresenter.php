<?php

namespace Corals\Modules\Utility\Transformers\ListOfValue;

use Corals\Foundation\Transformers\FractalPresenter;

class ListOfValuePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ListOfValueTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ListOfValueTransformer($extras);
    }
}
