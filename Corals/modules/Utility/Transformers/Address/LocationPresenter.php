<?php

namespace Corals\Modules\Utility\Transformers\Address;

use Corals\Foundation\Transformers\FractalPresenter;

class LocationPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return LocationTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new LocationTransformer($extras);
    }
}
