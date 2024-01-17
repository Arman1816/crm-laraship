<?php

namespace Corals\Modules\Marketplace\Facades;

use Illuminate\Support\Facades\Facade;

class OrderManager extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Marketplace\Classes\OrderManager::class;
    }
}
