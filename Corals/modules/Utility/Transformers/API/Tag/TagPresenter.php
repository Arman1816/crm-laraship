<?php

namespace Corals\Modules\Utility\Transformers\API\Tag;

use Corals\Foundation\Transformers\FractalPresenter;

class TagPresenter extends FractalPresenter
{

    /**
     * @return TagTransformer
     */
    public function getTransformer()
    {
        return new TagTransformer();
    }
}
