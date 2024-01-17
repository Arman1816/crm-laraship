<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TestimonialPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return TestimonialTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new TestimonialTransformer($extras);
    }
}
