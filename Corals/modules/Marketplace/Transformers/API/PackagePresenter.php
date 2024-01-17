<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class PackagePresenter extends FractalPresenter
{

    /**
     * @return PackageTransformer
     */
    public function getTransformer()
    {
        return new PackageTransformer();
    }
}
