<?php


namespace Corals\Modules\Subscriptions\Listeners;


use Corals\Modules\Payment\Common\Events\InvoicePaidEvent;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager;
use Corals\Modules\Subscriptions\Models\Subscription;

class InvoicePaidListener
{
    /**
     * @param InvoicePaidEvent $event
     */
    public function handle(InvoicePaidEvent $event)
    {
        $invoice = $event->invoice;
        $payload = $event->payLoad;

        $subscription = $invoice->invoicable;

        if (!($subscription instanceof Subscription)) {
            return;
        }

        SubscriptionsManager::activateSubscription($subscription);
    }
}
