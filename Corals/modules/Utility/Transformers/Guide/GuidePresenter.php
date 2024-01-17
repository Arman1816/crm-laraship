<?php

namespace Corals\Modules\Utility\Transformers\Guide;

use Corals\Foundation\Transformers\FractalPresenter;

class GuidePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return GuideTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new GuideTransformer($extras);
    }
}
