<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Subscriptions\Models\SubscriptionCycle;

class SubscriptionCycleTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('subscriptions.models.subscription_cycle.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param SubscriptionCycle $subscriptionCycle
     * @return array
     * @throws \Throwable
     */
    public function transform(SubscriptionCycle $subscriptionCycle)
    {
        $isCurrentSubscription = false;

        if (
            ($subscriptionCycle->starts_at && $subscriptionCycle->starts_at->lte(now()))
            &&
            ($subscriptionCycle->ends_at && $subscriptionCycle->ends_at->gte(now()))
        ) {
            $isCurrentSubscription = true;
        }

        $transformedArray = [
            'id' => $subscriptionCycle->id,

            'starts_at' => format_date_time($subscriptionCycle->starts_at),
            'ends_at' => format_date_time($subscriptionCycle->ends_at),
            'subscription' => $subscriptionCycle->subscription->getInvoiceReference(),
            'updated_at' => format_date($subscriptionCycle->updated_at),

            'current_cycle' => $isCurrentSubscription ? '<i class="fa fa-fw fa-check text-success"></i>' : '-',

            'created_at' => format_date($subscriptionCycle->created_at),
            'action' => $this->actions($subscriptionCycle)
        ];

        return parent::transformResponse($transformedArray, $subscriptionCycle);
    }
}
