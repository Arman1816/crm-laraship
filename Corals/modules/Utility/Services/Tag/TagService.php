<?php

namespace Corals\Modules\Utility\Services\Tag;

use Corals\Foundation\Services\BaseServiceClass;

class TagService extends BaseServiceClass
{
    protected $excludedRequestParams = ['thumbnail', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $tag = $this->model;

        if ($request->has('clear') || $request->hasFile('thumbnail')) {
            $tag->clearMediaCollection($tag->mediaCollectionName);
        }

        if ($request->hasFile('thumbnail') && !$request->has('clear')) {
            $tag->addMedia($request->file('thumbnail'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($tag->mediaCollectionName);
        }
    }
}
