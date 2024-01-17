<?php

namespace Corals\Modules\CMS\Services;


use Corals\Foundation\Services\BaseServiceClass;

class NewsService extends BaseServiceClass
{
    protected $excludedRequestParams = ['featured_image', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $news = $this->model;

        if ($request->has('clear') || $request->hasFile('featured_image')) {
            $news->clearMediaCollection('featured-image');
        }

        if ($request->hasFile('featured_image') && !$request->has('clear')) {
            $news->addMedia($request->file('featured_image'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection('featured-image');
        }
    }
}
