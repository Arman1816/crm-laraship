<?php

namespace Corals\Modules\Referral\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ReferralRelationshipPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ReferralRelationshipTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ReferralRelationshipTransformer($extras);
    }
}
