<?php

namespace Corals\Modules\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class Shipping extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Marketplace\Classes\Shipping::class;
    }
}
