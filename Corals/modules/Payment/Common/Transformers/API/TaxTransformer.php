<?php

namespace Corals\Modules\Payment\Common\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Payment\Common\Models\Tax;

class TaxTransformer extends APIBaseTransformer
{
    /**
     * @param Tax $tax
     * @return array
     * @throws \Throwable
     */
    public function transform(Tax $tax)
    {
        $transformedArray = [
            'id' => $tax->id,
            'name' => $tax->name,
            'tax_class' => $tax->tax_class->name,
            'status' => $tax->status,
            'priority' => $tax->priority,
            'country' => $tax->country,
            'state' => $tax->state,
            'zip' => $tax->zip,
            'compound' => $tax->compound,
            'rate' => $tax->rate . '%',
            'created_at' => format_date($tax->created_at),
            'updated_at' => format_date($tax->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
