<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Marketplace\Facades\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BrandService extends BaseServiceClass
{
    protected $excludedRequestParams = ['thumbnail', 'clear'];


    /**
     * @param $request
     * @param $additionalData
     * @throws ValidationException
     */
    public function preStore($request, &$additionalData)
    {
        if (!Store::isStoreAdmin()) {
            $store = Store::getVendorStore();

            if (!$store) {
                $validator = Validator::make([], []); // Empty data and rules fields

                $validator->errors()->add('store_id', trans('Marketplace::exception.store.invalid_store'));

                throw new ValidationException($validator);
            }

            $additionalData['store_id'] = $store->id;
        }
    }

    public function postStoreUpdate($request, $additionalData = [])
    {
        $brand = $this->model;

        if ($request->has('clear') || $request->hasFile('thumbnail')) {
            $brand->clearMediaCollection($brand->mediaCollectionName);
        }

        if ($request->hasFile('thumbnail') && !$request->has('clear')) {
            $brand->addMedia($request->file('thumbnail'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($brand->mediaCollectionName);
        }
    }
}
