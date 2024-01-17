<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class PostPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return PostTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new PostTransformer($extras);
    }
}
