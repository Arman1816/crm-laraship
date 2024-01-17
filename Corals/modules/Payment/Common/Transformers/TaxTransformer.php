<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\Tax;

class TaxTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_route = config('payment_common.models.tax.resource_route');

        parent::__construct($extras);
    }

    /**
     * @param Tax $tax
     * @return array
     * @throws \Throwable
     */
    public function transform(Tax $tax)
    {
        $transformedArray = [
            'id' => $tax->id,
            'name' => \Str::limit($tax->name, 50),
            'status' => formatStatusAsLabels($tax->status),
            'priority' => $tax->priority,
            'country' => $tax->country,
            'state' => $tax->state,
            'zip' => $tax->zip,
            'compound' => $tax->compound ? '<i class="fa fa-check text-success"></i>' : '-',
            'rate' => $tax->rate . '%',
            'created_at' => format_date($tax->created_at),
            'updated_at' => format_date($tax->updated_at),
            'action' => $this->actions($tax)
        ];

        return parent::transformResponse($transformedArray);
    }
}
