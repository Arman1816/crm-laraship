<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class BlockPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return BlockTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new BlockTransformer($extras);
    }
}
