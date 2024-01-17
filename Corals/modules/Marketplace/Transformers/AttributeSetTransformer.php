<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\AttributeSet;

class AttributeSetTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.attribute_set.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param AttributeSet $attributeSet
     * @return array
     * @throws \Throwable
     */
    public function transform(AttributeSet $attributeSet)
    {
        $transformedArray = [
            'id' => $attributeSet->id,
            'code' => $attributeSet->code,
            'name' => $attributeSet->name,
            'store' => $attributeSet->store ? $attributeSet->store->name : '-',
            'is_default' => $attributeSet->is_default ? '<i class="fa fa-check text-success"></i>' : '-',
            'created_at' => format_date($attributeSet->created_at),
            'updated_at' => format_date($attributeSet->updated_at),
            'action' => $this->actions($attributeSet)
        ];

        return parent::transformResponse($transformedArray);
    }
}
