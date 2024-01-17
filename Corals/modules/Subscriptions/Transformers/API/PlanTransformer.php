<?php

namespace Corals\Modules\Subscriptions\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Subscriptions\Models\Plan;

class PlanTransformer extends APIBaseTransformer
{
    /**
     * @param Plan $plan
     * @return array
     * @throws \Throwable
     */
    public function transform(Plan $plan)
    {
        $features = (new FeaturePresenter())->present($plan->activeFeatures)['data'];

        $transformedArray = [
            'id' => $plan->id,
            'name' => $plan->name,
            'subscribed' => user() ? user()->subscribed(null, $plan->id) : null,
            'price' => \Payments::currency($plan->price),
            'bill_frequency' => $plan->bill_frequency,
            'bill_cycle' => $plan->cycle_caption,
            'status' => $plan->status,
            'recommended' => $plan->recommended,
            'is_visible' => $plan->is_visible,
            'display_order' => $plan->display_order,
            'description' => $plan->description,
            'gateway_status' => $plan->getGatewayStatus(),
            'free_plan' => $plan->free_plan,
            'created_at' => format_date($plan->created_at),
            'updated_at' => format_date($plan->updated_at),
            'features' => $features,
        ];

        return parent::transformResponse($transformedArray);
    }
}
