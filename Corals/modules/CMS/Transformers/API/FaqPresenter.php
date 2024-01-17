<?php
namespace Corals\Modules\CMS\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class FaqPresenter extends FractalPresenter
{

    /**
     * @return FaqTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FaqTransformer();
    }
}
