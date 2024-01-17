<?php

namespace Corals\Modules\Marketplace\Classes\Shippings;

use Corals\Modules\Marketplace\Contracts\ShippingContract;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Settings\Facades\Settings;

/**
 * Class Fixed.
 */
class Shippo implements ShippingContract
{


    public $shipping_from;
    public $name;
    public $rule_id;
    public $sandbox;
    protected $shippingPerStore = false;
    protected $store;
    protected bool $skip = false;

    /**
     * Fixed constructor.
     *
     * @param $code
     * @param $value
     * @param array $options
     */
    public function __construct($options = [])
    {
    }

    public function methodClass()
    {
        return "Shippo";
    }

    /**
     * @param array $options
     * @return mixed|void
     * @throws \Exception
     */
    public function initialize($options = [])
    {
        $this->shippingPerStore = Settings::get('marketplace_shipping_shippo_per_store', true);

        if ($this->shippingPerStore) {
            $store = Store::find($options['store_id']);

            if ($store) {
                if ($store->getSettingValue('marketplace_shipping_shippo_sandbox_mode')) {
                    $this->sandbox = true;
                    $key = $store->getSettingValue('marketplace_shipping_shippo_test_token');
                } else {
                    $this->sandbox = false;
                    $key = $store->getSettingValue('marketplace_shipping_shippo_live_token');
                }
            }
        } else {
            if (data_get($options, 'gettingRules') && data_get($options, 'store_id')) {
                logger(sprintf('rule id [%s] related to store [%s] and shippo setting is not per store',
                    data_get($options, 'id'),
                    data_get($options, 'store_id')));
                $this->skip = true;
                return;
            }
            $store = Store::find($options['instance_id'] ?? ($options['store_id'] ?? ''));

            if (Settings::get('marketplace_shipping_shippo_sandbox_mode')) {
                $this->sandbox = true;
                $key = Settings::get('marketplace_shipping_shippo_test_token');
            } else {
                $key = Settings::get('marketplace_shipping_shippo_live_token');
                $this->sandbox = false;
            }
        }

        if (!$store) {
            throw new \Exception(trans('exception.shipping.invalid_store'));
        }

        \Shippo::setApiKey($key);

        $this->rule_id = $options['id'] ?? '';

        $this->store = $store;

        $this->shipping_from = [
            'name' => $store->getSettingValue('marketplace_company_owner') ?? '',
            'company' => $store->getSettingValue('marketplace_company_name') ?? '',
            'street1' => $store->getSettingValue('marketplace_company_street1') ?? '',
            'city' => $store->getSettingValue('marketplace_company_city') ?? '',
            'state' => $store->getSettingValue('marketplace_company_state') ?? '',
            'zip' => $store->getSettingValue('marketplace_company_zip') ?? '',
            'country' => $store->getSettingValue('marketplace_company_country') ?? '',
            'phone' => $store->getSettingValue('marketplace_company_phone') ?? '',
            'email' => $store->getSettingValue('marketplace_company_email') ?? '',
        ];
    }

    /**
     * Gets the shipping Rates.
     *
     * @param $throwErrors boolean this allows us to capture errors in our code if we wish,
     * that way we can spit out why the coupon has failed
     *
     * @return array
     */
    public function getAvailableShippingRates($to_address, $shippable_items, $user = null)
    {
        if ($this->skip) {
            return [];
        }
        $shipping_to = [
            'name' => sprintf("%s %s", data_get($to_address, 'first_name'), data_get($to_address, 'last_name')),
            'street1' => $to_address['address_1'],
            'city' => $to_address['city'],
            'state' => $to_address['state'],
            'zip' => $to_address['zip'],
            'country' => $to_address['country'],
            'phone' => $to_address['phone_number']

        ];

        $parcels = [];
        // You can now show those rates to the user in your UI.
        // Most likely you want to show some of the following fields:
        //  - provider (carrier name)
        //  - servicelevel_name
        //  - amount (price of label - you could add e.g. a 10% markup here)
        //  - days (transit time)
        // Don't forget to store the `object_id` of each Rate so that you can use it for the label purchase later.
        // The details on all of the fields in the returned object are here: https://goshippo.com/docs/reference#rates
        $available_rates = [];


        foreach ($shippable_items as $cart_item) {
            $packageId = $cart_item->id->product->shipping['package_id'] ?? null;
            $parcels = [];
            $package = null;

            if ($packageId) {
                $package = Package::find($packageId);

                if ($package->integration_id) {
                    try {
                        \Shippo_Parcel::retrieve($package->integration_id);

                        foreach (range(1, $cart_item->qty) as $i) {
                            $parcels[] = $package->integration_id;
                        }

                        continue;
                    } catch (\Exception $exception) {
                        logger('getAvailableShipping@Shippo_Parcel::retrieve');
                        logger($exception->getMessage());
                    }
                }
            }

            $productShipping = $cart_item->id->product->shipping;

            $productParcels = [
                'length' => $package && $package->length ? $package->length : data_get($productShipping, 'length'),
                'width' => $package && $package->width ? $package->width : data_get($productShipping, 'width'),
                'height' => $package && $package->height ? $package->height : data_get($productShipping, 'height'),
                'distance_unit' => $package && $package->distance_unit ? $package->distance_unit : \Settings::get('marketplace_shipping_dimensions_unit',
                    'in'),
                'weight' => $package && $package->weight ? $package->weight : data_get($productShipping, 'weight'),
                'mass_unit' => $package && $package->mass_unit ? $package->mass_unit : \Settings::get('marketplace_shipping_weight_unit',
                    'oz'),
            ];

            $parcel = [
                'length' => $cart_item->id->shipping['length'] ?? $productParcels['length'],
                'width' => $cart_item->id->shipping['width'] ?? $productParcels['width'],
                'height' => $cart_item->id->shipping['height'] ?? $productParcels['height'],
                'distance_unit' => $productParcels['distance_unit'],
                'weight' => $cart_item->id->shipping['weight'] ?? $productParcels['weight'],
                'mass_unit' => $productParcels['mass_unit'],
            ];

            foreach (range(1, $cart_item->qty) as $i) {
                $parcels[] = $parcel;
            }

            foreach ($parcels as $parcel) {
                $shipment = \Shippo_Shipment::create(
                    array(
                        'address_from' => $this->shipping_from,
                        'address_to' => $shipping_to,
                        'parcels' => $parcel,
                        'async' => false,
                    ));

                // Rates are stored in the `rates` array inside the shipment object
                $rates = $shipment['rates'];
//                \Logger($shipment);

                $allProviders = config('marketplace.store_settings.Shipping.preferred_providers.options', []);

                foreach ($rates as $rate) {
//                    if ($this->shippingPerStore) {
                    if ($this->store) {
                        $preferredProviders = $this->store->getSettingValue('marketplace_shipping_preferred_providers');

                        if (!$preferredProviders) {
                            $preferredProviders = Settings::get('marketplace_shipping_preferred_providers');
                        }

                        if ($preferredProviders) {
                            $preferredProviders = array_filter(array_flip($allProviders),
                                function ($item) use ($preferredProviders) {
                                    return in_array($item, $preferredProviders);
                                });

                            if (!in_array($rate['provider'], $preferredProviders) &&
                                !in_array($rate['provider'], array_flip($preferredProviders))
                            ) {
                                continue;
                            }
                        }
                    }

                    $key = $this->methodClass() . '|' . $rate['provider'] . '|' . $rate['servicelevel']['name'] . '|' . $cart_item->getHash();
                    if (array_key_exists($key, $available_rates)) {
                        $available_rates[$key]['amount'] += $rate['amount'];
                        $available_rates[$key]['qty'] += 1;
                        $available_rates[$key]['shipping_ref_id'][] = $rate['object_id'];
                    } else {
                        $available_rates[$key] = [
                            'provider' => $rate['provider'],
                            'shipping_method' => $this->methodClass(),
                            'service' => $rate['servicelevel']['name'],
                            'shipping_ref_id' => [$rate['object_id']],
                            'currency' => $rate['currency'],
                            'amount' => $rate['amount'],
                            'qty' => 1,
                            'shipping_rule_id' => $this->rule_id,
                            'product_id' => $cart_item->id->product->id,
                            'product_name' => $cart_item->id->product->name,
                            'cart_ref_id' => $cart_item->getHash(),
                            'description' => '',
                            'estimated_days' => $rate['estimated_days']
                        ];
                    }
                }
            }
        }


        return $available_rates;
    }


    public function createShippingTransaction($shipping_reference)
    {
        $transaction = [];

        try {
            $transaction = \Shippo_Transaction::create(array(
                'rate' => $shipping_reference,
                'async' => false,
            ));
        } catch (\Exception $exception) {
            $transaction['messages'] = [
                $exception->getMessage(),
            ];
        }

        $shipping = [];

        // Print the shipping label from label_url
        // Get the tracking number from tracking_number

        if (data_get($transaction, 'status') == 'SUCCESS') {
            $shipping['status'] = 'success';
            $shipping['label_url'] = $transaction['label_url'];
            $shipping['tracking_number'] = $transaction['tracking_number'];
        } else {
            $shipping['status'] = 'error';
            $shipping['error_message'] = '';

            foreach ($transaction['messages'] as $message) {
                $shipping['error_message'] .= $message . "<br>";
            }
        }

        return $shipping;
    }

    public function track($tracking_details)
    {
        try {
            if ($this->sandbox) {
                $status_params = array(
                    'carrier' => 'shippo',
                    'id' => 'SHIPPO_DELIVERED'
                );
            } else {
                $status_params = array(
                    'id' => $tracking_details['tracking_number'],
                    'carrier' => $tracking_details['provider'],
                );
            }


            $status = \Shippo_Track::get_status($status_params);
            $status_history = $status['tracking_history'];
            $history = [];

            foreach ($status_history as $status_history_item) {
                $history[] = [
                    'status' => $status_history_item['status'],
                    'status_details' => $status_history_item['status_details'],
                    'status_date' => $status_history_item['status_date'],
                    'status_location' => [
                        'city' => $status_history_item['location']['city'],
                        'state' => $status_history_item['location']['state'],
                        'zip' => $status_history_item['location']['zip'],
                        'country' => $status_history_item['location']['country'],

                    ],
                ];
            }

            $tracking_status = [
                'status' => $status['tracking_status']['status'],
                'status_details' => $status['tracking_status']['status'],
                'status_date' => $status['tracking_status']['status_date'],
                'status_location' => [
                    'city' => $status['tracking_status']['location']['city'],
                    'state' => $status['tracking_status']['location']['state'],
                    'zip' => $status['tracking_status']['location']['zip'],
                    'country' => $status['tracking_status']['location']['country'],

                ],
                'history' => $history
            ];

            return $tracking_status;
        } catch (\Shippo_Error $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
