<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class WebhookCallPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return WebhookCallTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new WebhookCallTransformer($extras);
    }
}
