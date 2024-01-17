<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class PackagePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return PackageTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new PackageTransformer($extras);
    }
}
