<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Marketplace\Facades\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PackageService extends BaseServiceClass
{
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

    public function postStore($request, &$additionalData)
    {
        $package = $this->model;

        if ($store = $package->store) {
            if ($store->getSettingValue('marketplace_shipping_shippo_sandbox_mode')) {
                $key = $store->getSettingValue('marketplace_shipping_shippo_test_token');
            } else {
                $key = $store->getSettingValue('marketplace_shipping_shippo_live_token');
            }

            if (empty($key)) {
                return;
            }

            try {
                \Shippo::setApiKey($key);

                $result = \Shippo_Parcel::create([
                    'length' => $package->length,
                    'width' => $package->width,
                    'height' => $package->height,
                    'distance_unit' => $package->distance_unit,
                    'weight' => $package->weight ?? '',
                    'mass_unit' => $package->mass_unit ?? '',
                    'template' => $package->dimension_template,
                    'metadata' => $package->name,
                ]);

                $integration_id = $result->offsetGet('object_id');

                $package->integration_id = $integration_id;

                $package->save();
            } catch (\Exception $exception) {
                report($exception);
            }
        }
    }
}
