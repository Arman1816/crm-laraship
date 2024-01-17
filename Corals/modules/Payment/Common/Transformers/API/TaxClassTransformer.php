<?php

namespace Corals\Modules\Payment\Common\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Payment\Common\Models\TaxClass;

class TaxClassTransformer extends APIBaseTransformer
{
    /**
     * @param TaxClass $tax_class
     * @return array
     * @throws \Throwable
     */
    public function transform(TaxClass $tax_class)
    {
        $transformedArray = [
            'id' => $tax_class->id,
            'name' => $tax_class->name,
            'taxes_count' => $tax_class->taxes()->count(),
            'created_at' => format_date($tax_class->created_at),
            'updated_at' => format_date($tax_class->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
