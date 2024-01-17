<?php


namespace Corals\Modules\Subscriptions\Classes;

use Corals\Modules\Payment\Common\AbstractGateway;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\Modules\Subscriptions\Models\SubscriptionCycle;

class SubscriptionsManager
{
    /**
     * @param Subscription $subscription
     * @param null $gateway
     * @param null $response
     * @param null $referenceId
     * @return Invoice|mixed
     */
    public function generateInvoice(
        Subscription $subscription,
        $gateway = null,
        $response = null,
        $referenceId = null
    ): Invoice {
        $plan = $subscription->plan;

        $invoice = $subscription->invoices()->create([
            'code' => Invoice::getCode('INV'),
            'currency' => \Payments::session_currency(),
            'description' => sprintf("%s subscription invoice", $plan->description),
            'sub_total' => $plan->price,
            'total' => $plan->price,
            'reference_id' => $referenceId,
            'user_id' => $subscription->user_id,
            'due_date' => now(),
            'invoice_date' => now(),
            'status' => 'pending',
            'properties' => [
                'gateway' => $gateway instanceof AbstractGateway ? $gateway->getName() : $gateway,
                'gateway_response' => $response,
            ],
        ]);

        $invoiceItems[] = [
            'code' => $plan->code,
            'amount' => $plan->price,
            'description' => $plan->description,
            'itemable_type' => getMorphAlias($plan),
            'itemable_id' => $plan->id
        ];

        $invoice->items()->createMany($invoiceItems);

        return $invoice;
    }

    /**
     * @param Subscription $subscription
     * @return bool
     */
    public function isSubscriptionHasPendingInvoice(Subscription $subscription): bool
    {
        $latestInvoice = $subscription->invoice;

        return $latestInvoice && $latestInvoice->status == 'pending';
    }

    /**
     * @param Subscription $subscription
     */
    public function activeSubscriptionWithInvoice(Subscription $subscription): void
    {
        $latestInvoice = $subscription->invoice;

        $latestInvoice->markAsPaid();
    }

    public function activateSubscription($subscription)
    {
        $forceCycle = true;

        if ($subscription->status != 'active') {
            $subscription->update(['status' => 'active', 'ends_at' => null]);
            $forceCycle = false;
        }

        $this->generateCycle($subscription, $forceCycle);
    }

    /**
     * @param $subscription
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentCycle($subscription)
    {
        if ($subscription->valid() && $currentCycle = $subscription->currentCycle()) {
            return $currentCycle;
        }

        throw new \Exception("Invalid Subscription Cycle: {$subscription->subscription_reference} [{$subscription->id}]");
    }

    /**
     * @param $subscription
     * @param false $force
     */
    public function generateCycle($subscription, $force = false)
    {
        $currentCycle = $subscription->currentCycle();

        if ($currentCycle && !$force) {
            return;
        }

        if ($currentCycle) {
            $startDate = $currentCycle->ends_at;
        } elseif ($lastCycle = $subscription->lastCycle()) {
            $startDate = $lastCycle->ends_at;
        } else {
            $startDate = $subscription->created_at;
        }

        if ($subscription->ends_at) {
            $nextCycleDate = $startDate->copy()->addDays($subscription->remainingDays());
        } else {
            $nextCycleDate = $subscription->getNextCycleDate($startDate);
        }

        SubscriptionCycle::query()->create([
            'subscription_id' => $subscription->id,
            'starts_at' => $startDate,
            'ends_at' => $nextCycleDate,
        ]);
    }
}
