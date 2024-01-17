<?php

namespace Corals\Modules\Subscriptions\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Subscriptions\Models\Subscription;

class SubscriptionTransformer extends APIBaseTransformer
{
    /**
     * @param Subscription $subscription
     * @return array
     * @throws \Throwable
     */
    public function transform(Subscription $subscription)
    {
        $plan = $subscription->plan;

        $product = $subscription->plan->product;

        if ($subscription->pending()) {
            $status = trans('Subscriptions::attributes.subscription.subscription_statuses.pending');
        } elseif ($subscription->active()) {
            $status = trans('Subscriptions::attributes.subscription.subscription_statuses.active');
        } else {
            $status = trans('Subscriptions::attributes.subscription.subscription_statuses.cancelled');
        }

        $transformedArray = [
            'id' => $subscription->id,
            'user' => $subscription->user->full_name,
            'plan' => $plan->name,
            'gateway' => $subscription->gateway,
            'product' => $product->name,
            'trial_ends_at' => format_date($subscription->trial_ends_at) ?: '-',
            'ends_at' => format_date($subscription->ends_at) ?: '-',
            'status' => $status,
            'on_trial' => $subscription->onTrial(),
            'created_at' => format_date($subscription->created_at),
            'updated_at' => format_date($subscription->updated_at),
            'subscription_reference' => $subscription->subscription_reference,
        ];

        return parent::transformResponse($transformedArray);
    }
}
