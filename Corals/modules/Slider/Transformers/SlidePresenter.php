<?php

namespace Corals\Modules\Slider\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class SlidePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SlideTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SlideTransformer($extras);
    }
}
