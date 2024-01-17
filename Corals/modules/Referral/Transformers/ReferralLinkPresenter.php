<?php

namespace Corals\Modules\Referral\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ReferralLinkPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ReferralLinkTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ReferralLinkTransformer($extras);
    }
}
