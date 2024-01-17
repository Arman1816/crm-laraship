<?php

namespace Corals\Modules\Messaging\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class DiscussionPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return DiscussionTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new DiscussionTransformer($extras);
    }
}
