<?php

namespace Corals\Modules\CMS\Services;


use Corals\Foundation\Services\BaseServiceClass;

class PostService extends BaseServiceClass
{
    protected $excludedRequestParams = ['featured_image', 'categories', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $post = $this->model;

        if ($request->has('clear') || $request->hasFile('featured_image')) {
            $post->clearMediaCollection('featured-image');
        }

        if ($request->hasFile('featured_image') && !$request->has('clear')) {
            $post->addMedia($request->file('featured_image'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection('featured-image');
        }

        $post->categories()->sync($request->get('categories', []));
    }
}
