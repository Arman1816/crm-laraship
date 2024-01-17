<?php

namespace Corals\Modules\Messaging\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ParticipationPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return ParticipationsTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new ParticipationsTransformer($extras);
    }
}
