<?php

namespace Corals\Modules\Subscriptions\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Subscriptions\Models\Feature;

class FeatureTransformer extends APIBaseTransformer
{
    /**
     * @param Feature $feature
     * @return array
     * @throws \Throwable
     */
    public function transform(Feature $feature)
    {
        $transformedArray = [
            'id' => $feature->id,
            'display_order' => $feature->display_order,
            'plan_value' => $feature->pivot->value,
            'name' => $feature->name,
            'caption' => $feature->caption,
            'unit' => $feature->unit,
            'status' => $feature->status,
            'type' => ucfirst($feature->type),
            'description' => $feature->description,
            'created_at' => format_date($feature->created_at),
            'updated_at' => format_date($feature->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
