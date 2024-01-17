<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class DownloadPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return DownloadTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new DownloadTransformer($extras);
    }
}
