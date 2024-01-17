<?php

namespace Corals\Modules\Utility\Facades\SEO;
use Illuminate\Support\Facades\Facade;

class SEOItems extends Facade
{

    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Utility\Classes\SEO\SEOItems::class;
    }

}
