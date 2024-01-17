<?php

namespace Corals\Modules\Utility\Services\Category;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Utility\Models\Category\AttributeOption;

class AttributeService extends BaseServiceClass
{
    protected $excludedRequestParams = ['thumbnail', 'clear', 'options'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $attribute = $this->model;

        $options = $request->get('options', []);

        $attribute_options = array_flip($attribute->options()->pluck('id')->toArray());

        foreach ($options as $option) {
            $option_id = $option['id'] ?? null;
            unset($attribute_options[$option_id]);
            AttributeOption::query()->updateOrCreate(['id' => $option_id, 'attribute_id' => $attribute->id], $option);
        }

        if (!empty($attribute_options)) {
            $attribute->options()->whereIn('id', array_keys($attribute_options))->forceDelete();
        }

        if ($request->has('clear') || $request->hasFile('thumbnail')) {
            $attribute->clearMediaCollection($attribute->mediaCollectionName);
        }

        if ($request->hasFile('thumbnail') && !$request->has('clear')) {
            $attribute->addMedia($request->file('thumbnail'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($attribute->mediaCollectionName);
        }
    }
}
