<?php


namespace Corals\Modules\Subscriptions\Listeners;


use Corals\Modules\Payment\Common\Message\AbstractResponse;
use Corals\Modules\Subscriptions\Events\SubscriptionCreated;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager;

class HandleSubscriptionCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param SubscriptionCreated $event
     */
    public function handle(SubscriptionCreated $event)
    {
        $subscription = $event->subscription;
        $gateway = $event->gateway;
        $response = $event->response;

        if ($gateway->getConfig('subscription_self_managed')) {
            if ($response instanceof AbstractResponse) {
                $referenceId = $response->getChargeReference();
                $response = $response->getData();
            } else {
                $referenceId = data_get($response, 'referenceId');
            }

            $invoice = SubscriptionsManager::generateInvoice($subscription, $gateway, $response, $referenceId);
        }

        if ($subscription->status === 'active') {
            SubscriptionsManager::generateCycle($subscription);
        }
    }
}
