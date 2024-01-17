<?php

namespace Corals\Modules\Marketplace\Classes;

use Corals\Modules\Marketplace\Models\Shipping as ShippingModel;
use Corals\Modules\Marketplace\Traits\ShippingTrait;
use Illuminate\Support\Arr;


class Shipping
{
    use ShippingTrait;


    public function getAvailableShippingMethods(
        $shipping_address,
        $shippable_items,
        $order_total,
        $store_id,
        $isPerProductShipping = false,
        $excludedShippingRules = []
    ) {
        $country = $shipping_address['country'];

        $applied_methods = [];
        $available_rates = [];
        $continue_shipping_scan = true;

        $total_weight = 0;
        $total_quantity = 0;

        $shipping_method_items = [];
        $shipping_method_rules = [];

        foreach ($shippable_items as $index => $shippable_item) {
            $productLevel = data_get($shippable_item->id->product->shipping, 'shipping_option') == 'flat_rate_prices';
            $weight = $shippable_item->id->shipping['weight'] ?? $shippable_item->id->product->shipping['weight'] ?? 0;


            if ($productLevel) {
                $shipping_rule = $shippable_item->id->product->shippingRates()
                    ->where('country', $country)->first();

                if ($shipping_rule) {
                    $shipping_method_rules[$shipping_rule->shipping_method]['product_rates'][$shippable_item->getHash()] =
                        array_merge($shipping_rule->toArray(), [
                            'properties' => $shipping_rule->properties,
                        ]);
                    $shipping_method_items[$shipping_rule->shipping_method][$shippable_item->getHash()] = $shippable_item;
                }
                unset($shippable_items[$index]);
            } else {
                $total_quantity += $shippable_item->qty;
                $total_weight += $shippable_item->qty * $weight;
            }
        }


        foreach ($shipping_method_rules as $shipping_method_key => $shipping_method_rule) {
            $shipping_method = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $shipping_method_key);

            $shipping_method->initialize($shipping_method_rule);


            $shipping_method_rates = $shipping_method->getAvailableShippingRates($shipping_address,
                $shipping_method_items[$shipping_method_key]);
            $available_rates = array_merge($shipping_method_rates, $available_rates);
        }

        if (empty($shippable_items)) {
            return $available_rates;
        }

        $shipping_rolesQB = ShippingModel::where(function ($query) use ($country) {
            $query->where('country', $country)
                ->orWhereNull('country');
        })->where(function ($query) use ($store_id) {
            $query->where('store_id', $store_id)
                ->orWhereNull('store_id');
        })->orderBy('exclusive', 'DESC')
            ->orderBy('priority', 'asc')
            ->orderBy('name', 'asc');


        if ($isPerProductShipping) {
            $shippable_item = current($shippable_items);

            $excludedShippingRules = data_get($shippable_item->id->product->shipping, 'excluded_shipping_rules');
        }

        if ($excludedShippingRules) {
            $shipping_rolesQB->whereNotIn('id', $excludedShippingRules);
        }

        $shipping_roles = $shipping_rolesQB->get();

        if ($shipping_roles->isEmpty()) {
            return [];
        }

        foreach ($shipping_roles as $shipping_role) {
            try {
                if (!$continue_shipping_scan) {
                    continue;
                }

                if ($shipping_role->min_order_total) {
                    if ($order_total <= $shipping_role->min_order_total) {
                        continue;
                    }
                }

                if ($shipping_role->max_total_weight) {
                    if ($total_weight <= $shipping_role->max_total_weight) {
                        continue;
                    }
                }

                if ($shipping_role->min_total_quantity) {
                    if ($total_quantity < $shipping_role->min_total_quantity) {
                        continue;
                    }
                }

                if (!$this->isShippingMethodSupported($shipping_role->shipping_method)) {
                    continue;
                }

                if (in_array($shipping_role->name, $applied_methods)) {
                    continue;
                }

                $shipping_method = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $shipping_role->shipping_method);

                $shipping_method->initialize(array_merge($shipping_role->toArray(), [
                    'quantity' => $total_quantity,
                    'weight' => $total_weight,
                    'properties' => $shipping_role->properties,
                    'instance_id' => $store_id,
                    'gettingRules' => true,
                ]));

                $shipping_method_rates = $shipping_method->getAvailableShippingRates($shipping_address,
                    $shippable_items);

                if ($shipping_role->exclusive && (count($shipping_method_rates) > 0)) {
                    $available_rates = $shipping_method_rates;
                    $continue_shipping_scan = false;
                } else {
                    $available_rates = array_merge($shipping_method_rates, $available_rates);
                }

                $applied_methods[] = $shipping_role->name;
            } catch (\Exception $exception) {
                report($exception);
            }
        }

        return $available_rates;
    }


    public function getmethodClass($selected_shipping)
    {
        list($shipping_method, $shipping_object, $shipping_rule_id) = explode('|', $selected_shipping);
        return $shipping_method;
    }

    public function track($order, $shipping_details)
    {
        if (!$shipping_details) {
            return [];
        }
        $method = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $shipping_details['shipping_method']);
        $method->initialize(['store_id' => $order->store_id]);
        return $method->track($shipping_details);
    }

    public function createShippingTransaction($selected_shipping)
    {
        $shipping_method = $selected_shipping->getProperty('shipping_method');
        $shipping_provider = $selected_shipping->getProperty('provider');

        $shipping_method_class = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $shipping_method);

        $selected_shipping_properties = $selected_shipping->properties ?? [];
        $shipping_options = array_merge($selected_shipping_properties,
            ['store_id' => $selected_shipping->order->store_id]);
        $shipping_method_class->initialize($shipping_options);

        if (!is_array($selected_shipping->getProperty('shipping_ref_id'))) {
            $shipping_refs = [$selected_shipping->getProperty('shipping_ref_id')];
        } else {
            $shipping_refs = $selected_shipping->getProperty('shipping_ref_id');
        }
        $shipping_transactions = [];
        foreach ($shipping_refs as $shipping_reference) {
            $shipment_transaction = $shipping_method_class->createShippingTransaction($shipping_reference);
            if (isset($shipment_transaction['tracking_number'])) {
                $shipping_transactions[$shipment_transaction['tracking_number']] = array_merge(
                    [
                        'product_id' => $selected_shipping->getProperty('product_id'),
                        'product_name' => $selected_shipping->getProperty('product_name'),
                        'shipping_method' => $shipping_method,
                        'shipping_provider' => $shipping_provider

                    ], $shipment_transaction);
            } else {
                $shipping_transactions[$shipping_reference] = $shipment_transaction;
            }
        }
        return $shipping_transactions;
    }

    public function getShippingMethods($except = [])
    {
        $supported_shipping_methods = Arr::except(\Settings::get('supported_shipping_methods', []), $except);

        return $supported_shipping_methods;
    }

    public function setShippingMethods($supported_shipping_methods)
    {
        \Settings::set('supported_shipping_methods', json_encode($supported_shipping_methods));
    }


    public function isShippingMethodSupported($shipping_methods)
    {
        return array_key_exists($shipping_methods, $this->getShippingMethods());
    }

    public function getShippingRules($store = null)
    {
        if (is_int($store)) {
            $store = \Corals\Modules\Marketplace\Models\Store::find($store);
        } elseif (is_null($store)
            && user()
            && !user()->hasPermissionTo('Administrations::admin.marketplace')
        ) {
            $store = \Store::getVendorStore();
        }

        return ShippingModel::query()
            ->when($store, function ($query, $store) {
                $query->where('store_id', $store->id);
            })
            ->orWhereNull('store_id')->get();
    }
}
