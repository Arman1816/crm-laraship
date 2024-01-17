<?php

namespace Corals\Modules\Referral\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ReferralProgramPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ReferralProgramTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ReferralProgramTransformer($extras);
    }
}
