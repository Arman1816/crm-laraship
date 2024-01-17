<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class TagPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return TagTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new TagTransformer($extras);
    }
}
