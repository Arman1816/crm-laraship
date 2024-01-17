<?php

namespace Corals\Modules\Marketplace\Classes\Shippings;

use Corals\Modules\Marketplace\Contracts\ShippingContract;

/**
 * Class Fixed.
 */
class Free implements ShippingContract
{


    public $rate;
    public $description;
    public $name;
    public $rule_id;
    public $properties;
    public $product_rates;


    /**
     * Free constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
    }

    public function methodClass()
    {
        return "Free";
    }

    /**
     * @param array $options
     */
    public function initialize($options = [])
    {
        $this->name = $options['name'] ?? '';
        $this->rule_id = $options['id'] ?? '';
        $this->rate = $options['rate'] ?? '';
        $this->description = $options['description'] ?? '';
        $this->properties = $options['properties'] ?? [];
        $this->product_rates = $options['product_rates'] ?? [];
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
        foreach ($shippable_items as $cart_item) {
            if (array_key_exists($cart_item->getHash(), $this->product_rates)) {
                $rule_id = $this->product_rates[$cart_item->getHash()]['id'];
                $shippingProvider = $this->product_rates[$cart_item->getHash()]['properties']['shipping_provider'];

                if ($shippingProvider == 'other') {
                    $shippingProvider = $this->product_rates[$cart_item->getHash()]['properties']['other_shipping_provider'] ?? $shippingProvider;
                }

                $rule_name = $shippingProvider;
            } else {
                $rule_id = $this->rule_id;
                $rule_name = $this->name;
            }

            $key = $this->methodClass() . '|' . $this->name . '|' . $this->rule_id . "|" . $cart_item->getHash();

            $available_rates[$key] = [
                'shipping_method' => $this->methodClass(),
                'provider' => $rule_name,
                'description' => $this->description,
                'service' => '',
                'currency' => \Payments::admin_currency_code(),
                'amount' => '0.0',
                'qty' => $cart_item->qty,
                'shipping_rule_id' => $rule_id,
                'estimated_days' => '',
                'product_id' => $cart_item->id->product->id,
                'product_name' => $cart_item->id->product->name,
                'cart_ref_id' => $cart_item->getHash(),
            ];
        }

        return $available_rates;
    }

    public function createShippingTransaction($shipping_reference)
    {
        $shipping = [];

        $shipping['status'] = 'pending';
        $shipping['label_url'] = '';
        $shipping['tracking_number'] = '';

        return $shipping;
    }


    public function track($tracking_details)
    {
        try {
            $tracking_status = [];
            return $tracking_status;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
