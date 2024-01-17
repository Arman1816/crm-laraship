<?php

namespace Corals\Modules\Subscriptions\Facades;

use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Subscriptions\Classes\SubscriptionsManager as SubscriptionsManagerClass;
use Corals\Modules\Subscriptions\Models\Subscription;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Invoice generateInvoice(Subscription $subscription, $gateway = null, $response = null, $referenceId = null)
 * @method static bool isSubscriptionHasPendingInvoice(Subscription $subscription)
 * @method static Invoice activeSubscriptionWithInvoice(Subscription $subscription)
 * @method static getCurrentCycle($subscription)
 * @method static generateCycle($subscription, $force = false)
 * @method static activateSubscription($subscription)
 */
class SubscriptionsManager extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return SubscriptionsManagerClass::class;
    }
}
