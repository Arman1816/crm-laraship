<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Marketplace\Facades\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CouponService extends BaseServiceClass
{
    protected $excludedRequestParams = ['users', 'products'];

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
        $coupon = $this->model;

        $coupon->users()->sync($request->get('users', []));

        $coupon->products()->sync($request->get('products', []));
    }
}
