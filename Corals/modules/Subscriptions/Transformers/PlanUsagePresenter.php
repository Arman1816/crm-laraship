<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class PlanUsagePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return PlanUsageTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new PlanUsageTransformer($extras);
    }
}
