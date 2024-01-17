<?php

namespace Corals\Modules\Subscriptions\Facades;

use Illuminate\Support\Facades\Facade;

class UsageManager extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Subscriptions\Classes\UsageManager::class;
    }
}
