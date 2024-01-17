<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class WidgetPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return WidgetTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new WidgetTransformer($extras);
    }
}
