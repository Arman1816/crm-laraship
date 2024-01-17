<?php

namespace Corals\Modules\Subscriptions\Facades;

use Illuminate\Support\Facades\Facade;

class SubscriptionProducts extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Subscriptions\Classes\SubscriptionProducts::class;
    }
}
