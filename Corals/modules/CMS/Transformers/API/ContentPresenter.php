<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class ContentPresenter extends FractalPresenter
{

    /**
     * @return ContentTransformer
     */
    public function getTransformer()
    {
        return new ContentTransformer();
    }
}
