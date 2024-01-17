<?php

namespace Corals\Modules\Utility\Transformers\API\Category;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Utility\Models\Category\Attribute;

class AttributeTransformer extends APIBaseTransformer
{
    /**
     * @param Attribute $attribute
     * @return array
     * @throws \Throwable
     */
    public function transform(Attribute $attribute)
    {
        $categories = apiPluck($attribute->categories()->pluck('utility_categories.name', 'utility_categories.id'),
            'id', 'name');
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
            'type' => $attribute->type,
            'label' => $attribute->label,
            'required' => $attribute->required,
            'use_as_filter' => $attribute->use_as_filter,
            'created_at' => format_date($attribute->created_at),
            'updated_at' => format_date($attribute->updated_at),
            'categories' => $categories,
            'options' => $optionsList,
        ];

        return parent::transformResponse($transformedArray);
    }
}
