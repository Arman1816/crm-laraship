<?php

namespace Corals\Modules\Utility\Transformers\SEO;

use Corals\Foundation\Transformers\FractalPresenter;

class SEOItemPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SEOItemTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SEOItemTransformer($extras);
    }
}
