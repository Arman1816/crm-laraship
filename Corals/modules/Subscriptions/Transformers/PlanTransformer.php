<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Subscriptions\Models\Plan;

class PlanTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_route = config('subscriptions.models.plan.resource_route');

        parent::__construct($extras);
    }

    /**
     * @param Plan $plan
     * @return array
     * @throws \Throwable
     */
    public function transform(Plan $plan)
    {

        $actions = [];

        if (!$plan->free_plan) {
            $actions = $plan->getGatewayActions($plan);
        }

        $transformedArray = [
            'id' => $plan->id,
            'name' => $plan->name,
            'price' => $plan->price,
            'bill_frequency' => $plan->bill_frequency,
            'bill_cycle' => $plan->cycle_caption,
            'status' => formatStatusAsLabels($plan->status),
            'recommended' => $plan->recommended ? '<i class="fa fa-check text-success"></i>' : '-',
            'is_visible' => $plan->is_visible ? '<i class="fa fa-check text-success"></i>' : '-',
            'display_order' => $plan->display_order,
            'description' => generatePopover($plan->description),
            'notify_end_subscription_before' => $plan->notify_end_subscription_before ?? '-',
            'gateway_status' => $plan->getGatewayStatus(),
            'free_plan' => $plan->free_plan ? '<i class="fa fa-check text-success"></i>' : '-',
            'created_at' => format_date($plan->created_at),
            'updated_at' => format_date($plan->updated_at),
            'action' => $this->actions($plan, $actions)
        ];

        return parent::transformResponse($transformedArray);
    }
}
