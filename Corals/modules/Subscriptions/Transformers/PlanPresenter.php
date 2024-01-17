<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class PlanPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return PlanTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new PlanTransformer($extras);
    }
}
