<?php


namespace Corals\Modules\Marketplace\Classes;


use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;

class ShippingPackages
{

    public function getPackagesTemplates()
    {
        $templates = ListOfValues::get('marketplace_packages_templates', true);

        $templatesList = [];

        foreach ($templates as $template) {
            $templatesList[$template->code] = $template->value;
        }

        return $templatesList;
    }

    public function getAvailablePackages($data = []): array
    {
        $packages = Package::query();

        if (user()->hasRole('vendor')) {
            $store = Store::getVendorStore();

            $packages->where(function ($storeQB) use ($store) {
                $storeQB->where('store_id', optional($store)->id)
                    ->orWhereNull('store_id');
            });
        }

        if ($data) {
            $packages->where([
                'length' => $data['length'] ?? '',
                'width' => $data['width'] ?? '',
                'height' => $data['height'] ?? '',
            ]);
        }
        $packagesList = [];

        foreach ($packages->get() as $package) {
            $packagesList[$package->id] = str_replace(' - -', '', sprintf("%s (%s - %s)",
                $package->name,
                $package->present('dimensions'),
                $package->present('package_weight')
            ));
        }

        return $packagesList;
    }

    public function getFlatShippingRates($product)
    {
        $rates = [];

        foreach ($product->shippingRates as $rate) {
            $rates[] = [
                'name' => $rate->name,
                'one_item_price' => $rate->rate,
                'country' => $rate->country,
                'shipping_method' => $rate->shipping_method,
                'shipping_provider' => $rate->getProperty('shipping_provider'),
                'other_shipping_provider' => $rate->getProperty('other_shipping_provider'),
                'additional_item_price' => $rate->getProperty('additional_item_price'),
            ];
        }

        return $rates;
    }
}
