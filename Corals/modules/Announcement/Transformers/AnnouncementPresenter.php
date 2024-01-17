<?php

namespace Corals\Modules\Announcement\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class AnnouncementPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return AnnouncementTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new AnnouncementTransformer($extras);
    }
}
