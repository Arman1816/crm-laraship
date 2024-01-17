<?php

namespace Corals\Modules\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class Store extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return 'store';
    }
}
