<?php

namespace Corals\Modules\CMS\Services;


use Corals\Foundation\Services\BaseServiceClass;

class TestimonialService extends BaseServiceClass
{
    protected $excludedRequestParams = ['image', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $testimonial = $this->model;

        if ($request->has('clear') || $request->hasFile('image')) {
            $testimonial->clearMediaCollection($testimonial->mediaCollectionName);
        }

        if ($request->hasFile('image') && !$request->has('clear')) {
            $testimonial->addMedia($request->file('image'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($testimonial->mediaCollectionName);
        }
    }
}
