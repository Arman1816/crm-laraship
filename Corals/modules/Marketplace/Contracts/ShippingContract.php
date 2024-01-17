<?php

namespace Corals\Modules\Marketplace\Contracts;

/**
 * Interface ShippingContract.
 */
interface ShippingContract
{
    /**
     * ShippingContract constructor.
     *
     */
    public function __construct();


    /**
     * @param array $options
     * @return mixed
     */
    public function initialize($options = []);

    /**
     * Gets the Available Shipping methods.
     *
     * @return string
     */
    public function getAvailableShippingRates($to_address, $shippable_items, $user);


    /**
     * create tshipping Transaction
     *
     * @return double
     */
    public function createShippingTransaction($shippingReference);

    public function track($tracking_details);


    /**
     * Get provider Name
     *
     * @return string
     */
    public function methodClass();


}
