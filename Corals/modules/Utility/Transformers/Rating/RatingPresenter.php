<?php

namespace Corals\Modules\Utility\Transformers\Rating;

use Corals\Foundation\Transformers\FractalPresenter;

class RatingPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return RatingTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new RatingTransformer($extras);
    }
}
