<?php

namespace Corals\Modules\Utility\Transformers\API\Rating;

use Corals\Foundation\Transformers\FractalPresenter;

class RatingPresenter extends FractalPresenter
{

    /**
     * @return RatingTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RatingTransformer();
    }
}
