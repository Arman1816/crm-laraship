<?php

namespace Corals\Modules\Messaging\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class MessagesPresenter extends FractalPresenter
{
    /**
     * @param array $extras
     * @return MessageTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new MessageTransformer($extras);
    }
}
