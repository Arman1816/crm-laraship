<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class FaqPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return FaqTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new FaqTransformer($extras);
    }
}
