<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class FeaturePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return FeatureTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new FeatureTransformer($extras);
    }
}
