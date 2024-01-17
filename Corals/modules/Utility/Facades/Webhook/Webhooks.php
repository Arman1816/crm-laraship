<?php

namespace Corals\Modules\Utility\Facades\Webhook;

use Illuminate\Support\Facades\Facade;

class Webhooks extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Utility\Classes\Webhook\Webhooks::class;
    }
}
