<?php

namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class PostPresenter extends FractalPresenter
{

    /**
     * @return PostTransformer
     */
    public function getTransformer()
    {
        return new PostTransformer();
    }
}
