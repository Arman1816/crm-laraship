<?php

namespace Corals\Modules\Utility\Transformers\Category;

use Corals\Foundation\Transformers\FractalPresenter;

class CategoryPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return CategoryTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new CategoryTransformer($extras);
    }
}
