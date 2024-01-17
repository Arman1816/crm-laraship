<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\TaxClass;

class TaxClassTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('payment_common.models.tax_class.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param TaxClass $tax_class
     * @return array
     * @throws \Throwable
     */
    public function transform(TaxClass $tax_class)
    {
        $transformedArray = [
            'id' => $tax_class->id,
            'name' => '<a href="' . url($this->resource_url . '/' . $tax_class->hashed_id . '/taxes') . '">' . $tax_class->name . '</a>',
            'created_at' => format_date($tax_class->created_at),
            'taxes' => '<a href="'.url($this->resource_url . '/' . $tax_class->hashed_id . '/taxes').'">'.$tax_class->taxes()->count().'</a>',
            'updated_at' => format_date($tax_class->updated_at),
            'action' => $this->actions($tax_class),
        ];

        return parent::transformResponse($transformedArray);
    }


}
