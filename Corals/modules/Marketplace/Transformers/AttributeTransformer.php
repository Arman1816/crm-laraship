<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Attribute;

class AttributeTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.attribute.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Attribute $attribute
     * @return array
     * @throws \Throwable
     */
    public function transform(Attribute $attribute)
    {
        $transformedArray = [
            'id' => $attribute->id,
            'type' => trans(config('settings.models.custom_field_setting.supported_types')[$attribute->type] ?? '-'),
            'label' => \Str::limit($attribute->label),
            'store' => $attribute->store ? $attribute->store->name : '-',
            'required' => $attribute->required ? '<i class="fa fa-check text-success"></i>' : '-',
            'attribute_sets' => formatArrayAsLabels($attribute->attributeSets()->pluck('name')->toArray(), 'primary'),
            'created_at' => format_date($attribute->created_at),
            'updated_at' => format_date($attribute->updated_at),
            'action' => $this->actions($attribute)
        ];

        return parent::transformResponse($transformedArray);
    }
}
