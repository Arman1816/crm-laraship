<?php

namespace Corals\Modules\CMS\Services;


use Corals\Foundation\Services\BaseServiceClass;

class PageService extends BaseServiceClass
{
    protected $excludedRequestParams = ['featured_image', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $page = $this->model;

        if ($request->has('clear') || $request->hasFile('featured_image')) {
            $page->clearMediaCollection('featured-image');
        }

        if ($request->hasFile('featured_image') && !$request->has('clear')) {
            $page->addMedia($request->file('featured_image'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection('featured-image');
        }

        $page->categories()->sync($request->get('categories', []));

    }
}
