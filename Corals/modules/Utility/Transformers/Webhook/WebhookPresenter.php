<?php

namespace Corals\Modules\Utility\Transformers\Webhook;

use Corals\Foundation\Transformers\FractalPresenter;

class WebhookPresenter extends FractalPresenter
{

    /**
     * @return WebhookTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new WebhookTransformer();
    }
}
