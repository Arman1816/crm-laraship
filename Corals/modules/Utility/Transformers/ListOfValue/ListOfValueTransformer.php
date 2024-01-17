<?php

namespace Corals\Modules\Utility\Transformers\ListOfValue;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Illuminate\Support\Str;

class ListOfValueTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('utility.models.listOfValue.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param ListOfValue $listOfValue
     * @return array
     * @throws \Throwable
     */
    public function transform(ListOfValue $listOfValue)
    {
        $transformedArray = [
            'id' => $listOfValue->id,
            'checkbox' => $this->generateCheckboxElement($listOfValue),
            'code' => $listOfValue->code,
            'label' => $listOfValue->getRawOriginal('label') ?? '-',
            'value' => Str::limit($listOfValue->value),
            'status' => formatStatusAsLabels($listOfValue->status),
            'module' => $listOfValue->module ?? '-',
            'parent' => $listOfValue->parent ? Str::limit($listOfValue->parent->value) : '-',
            'hidden' => yesNoFormatter($listOfValue->hidden),
            'created_at' => format_date($listOfValue->created_at),
            'updated_at' => format_date($listOfValue->updated_at),
            'action' => $this->actions($listOfValue)
        ];

        return parent::transformResponse($transformedArray);
    }
}
