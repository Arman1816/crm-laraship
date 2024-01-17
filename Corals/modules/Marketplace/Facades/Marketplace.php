<?php

namespace Corals\Modules\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class Marketplace extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Marketplace\Classes\Marketplace::class;
    }
}
