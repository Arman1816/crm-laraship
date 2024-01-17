<?php

namespace Corals\Modules\Payment\Common\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class CurrencyPresenter extends FractalPresenter
{
    public function getTransformer()
    {
        return new CurrencyTransformer();
    }

}
