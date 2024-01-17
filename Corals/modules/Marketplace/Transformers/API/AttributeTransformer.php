<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Attribute;

class AttributeTransformer extends APIBaseTransformer
{
    /**
     * @param Attribute $attribute
     * @return array
     * @throws \Throwable
     */
    public function transform(Attribute $attribute)
    {
        $optionsList = [];

        foreach ($attribute->options as $option) {
            $optionsList [] = [
                "id" => $option->id,
                "option_order" => $option->option_order,
                "option_value" => $option->option_value,
                "option_display" => $option->option_display,
            ];
        }

        $transformedArray = [
            'id' => $attribute->id,
            'type' => trans(config('settings.models.custom_field_setting.supported_types')[$attribute->type] ?? null),
            'label' => \Str::limit($attribute->label),
            'required' => $attribute->required,
            'created_at' => format_date($attribute->created_at),
            'updated_at' => format_date($attribute->updated_at),
            'options' => $optionsList,
        ];

        return parent::transformResponse($transformedArray);
    }
}
