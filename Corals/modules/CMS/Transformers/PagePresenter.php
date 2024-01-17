<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class PagePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return PageTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new PageTransformer($extras);
    }
}
