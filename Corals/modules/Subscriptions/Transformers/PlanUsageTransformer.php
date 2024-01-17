<?php

namespace Corals\Modules\Subscriptions\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Subscriptions\Models\PlanUsage;

class PlanUsageTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('subscriptions.models.plan_usage.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param PlanUsage $planUsage
     * @return array
     * @throws \Throwable
     */
    public function transform(PlanUsage $planUsage)
    {

        $transformedArray = [
            'id' => $planUsage->id,
            'subscription' => $planUsage->subscription->present('subscription_reference'),
            'feature' => $planUsage->feature->present('name'),
            'details' => generatePopover(formatProperties($planUsage->usage_details)),
            'updated_at' => format_date($planUsage->updated_at),
            'created_at' => format_date($planUsage->created_at),
            'action' => $this->actions($planUsage)
        ];

        return parent::transformResponse($transformedArray, $planUsage);
    }
}
