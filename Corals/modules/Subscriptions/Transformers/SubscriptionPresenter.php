<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class SubscriptionPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SubscriptionTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SubscriptionTransformer($extras);
    }
}
