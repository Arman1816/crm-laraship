<?php

namespace Corals\Modules\Marketplace\Middleware;

use Closure;
use Corals\Modules\Marketplace\Facades\Store;


class MarketplacetMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {


        $current_store = Store::getCurrentStore($request, user());

        if ($current_store) {
            Store::setStore($current_store);
        }


        return $next($request);

    }
}
