<?php


namespace Corals\Modules\Marketplace\Traits;


trait OrderCommon
{
    protected function canAccess($order)
    {
        $canAccess = false;

        if ($order->user && $order->user->id == user()->id) {
            $canAccess = user()->hasPermissionTo('Marketplace::my_orders.access');
        } elseif (user()->hasPermissionTo('Marketplace::store_orders.access') && $order->store->user->id == user()->id) {
            $canAccess = true;
        } elseif (user()->hasPermissionTo('Marketplace::order.view')) {
            $canAccess = true;
        }

        if (!$canAccess) {
            abort(403, 'Unauthorized!!.');
        }
    }
}
