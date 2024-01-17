<?php

namespace Corals\Modules\Utility\Services\Category;

use Corals\Foundation\Services\BaseServiceClass;

class CategoryService extends BaseServiceClass
{
    protected $excludedRequestParams = ['thumbnail', 'clear', 'category_attributes'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $category = $this->model;

        $category->categoryAttributes()->sync($request->get('category_attributes', []));

        if ($request->has('clear') || $request->hasFile('thumbnail')) {
            $category->clearMediaCollection($category->mediaCollectionName);
        }

        if ($request->hasFile('thumbnail') && !$request->has('clear')) {
            $category->addMedia($request->file('thumbnail'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($category->mediaCollectionName);
        }
    }
}
