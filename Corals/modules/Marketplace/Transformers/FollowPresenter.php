<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class FollowPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return FollowTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new FollowTransformer($extras);
    }
}
