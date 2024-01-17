<?php

namespace Corals\Modules\CMS\Services;


use Corals\Foundation\Services\BaseServiceClass;

class FaqService extends BaseServiceClass
{
    public function postStoreUpdate($request, $additionalData = [])
    {
        $this->model->categories()->sync($request->get('categories'));
    }
}
