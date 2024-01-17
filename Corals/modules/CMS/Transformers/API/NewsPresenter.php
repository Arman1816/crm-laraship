<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class NewsPresenter extends FractalPresenter
{

    /**
     * @return NewsTransformer
     */
    public function getTransformer()
    {
        return new NewsTransformer();
    }
}
