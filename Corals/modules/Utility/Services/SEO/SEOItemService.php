<?php

namespace Corals\Modules\Utility\Services\SEO;

use Corals\Foundation\Services\BaseServiceClass;

class SEOItemService extends BaseServiceClass
{

    protected $excludedRequestParams = ['image', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $seo_item = $this->model;

        if ($request->has('clear') || $request->hasFile('image')) {
            $seo_item->clearMediaCollection($seo_item->mediaCollectionName);
        }

        if ($request->hasFile('image') && !$request->has('clear')) {
            $seo_item->addMedia($request->file('image'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($seo_item->mediaCollectionName);
        }
    }

}
