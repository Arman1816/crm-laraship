<?php

namespace Corals\Modules\Slider\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class SliderPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SliderTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SliderTransformer($extras);
    }
}
