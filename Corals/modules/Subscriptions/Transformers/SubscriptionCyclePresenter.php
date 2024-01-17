<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class SubscriptionCyclePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SubscriptionCycleTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SubscriptionCycleTransformer($extras);
    }
}
