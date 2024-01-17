<?php

namespace Corals\Modules\Marketplace\Classes\Coupons;

use Corals\Modules\Marketplace\Classes\ShoppingCart;
use Corals\Modules\Marketplace\Contracts\CouponContract;
use Corals\Modules\Marketplace\Exceptions\CouponException;
use Corals\Modules\Marketplace\Models\OrderItem;
use Corals\Modules\Marketplace\Traits\CouponTrait;

/**
 * Class Advanced.
 */
class Advanced implements CouponContract
{
    use CouponTrait;

    public $code;
    public $settings;

    /**
     * Fixed constructor.
     *
     * @param $code
     * @param $value
     * @param array $options
     */
    public function __construct($code, $settings, $options = [])
    {
        $this->code = $code;
        $this->settings = $settings;

        $this->setOptions($options);
    }


    /**
     * check the discount valid.
     * @param bool $throwErrors this allows us to capture errors in our code if we wish,
     * that way we can spit out why the coupon has failed
     * @param null $user
     * @return bool
     * @throws CouponException
     */
    public function validate($throwErrors = false, $user = null)
    {
        if (!$this->checkValidTimes($this->settings->start, $this->settings->expiry, $throwErrors)) {
            return false;
        }

        if ($this->settings->min_cart_total) {
            if (!is_api_request()) {
                if (!$this->checkMinAmount($this->settings->store_id, $this->settings->min_cart_total, $throwErrors)) {
                    return false;
                }
            }
        }

        if (!$user) {
            $user = user();
        }

        if ($this->settings->uses > 0) {
            $usage = OrderItem::where('type', 'Discount')->where('sku_code', $this->code)->count();
            if ($usage >= $this->settings->uses) {
                if ($throwErrors) {
                    throw new CouponException(trans('Marketplace::exception.coupon.code_reached_maximum'));
                } else {
                    return false;
                }
            }
        }

        if ($this->settings->users->count() > 0) {
            if (!$user) {
                return false;
            }
            if ($user && $this->settings->users->contains($user->id)) {
                return true;
            } else {
                if ($throwErrors) {
                    throw new CouponException(trans('Marketplace::exception.coupon.not_eligible_use_coupon'));
                } else {
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * @param bool $throwErrors
     * @param null $user
     * @return float|int|mixed|string
     * @throws CouponException
     */
    public function discount($throwErrors = false, $user = null)
    {
        $subTotal = app(ShoppingCart::SERVICE)->subTotal(false);


        $products_total_price = 0;
        $products_total_quantity = 0;
        $product_limited = false;
        if ($this->settings->products->count() > 0) {
            $product_limited = true;

            $cart_items = \ShoppingCart::getAllInstanceItems();
            foreach ($cart_items as $cart_item) {
                if ($this->settings->products->contains($cart_item->id->product->id)) {
                    $products_total_price += ($cart_item->qty * $cart_item->price);
                    $product_limited += $cart_item->qty;
                }
            }

        }
        $discount = 0;

        if ($this->settings->type == "percentage") {

            if ($product_limited) {
                $subTotal = $products_total_price;
            }
            $discount = $subTotal * ($this->settings->value / 100);

        } else if ($this->settings->type == "fixed") {

            $discount = $this->settings->value;

        }

        if ($this->settings->max_discount_value) {
            // Returns either the max discount or the discount applied based on what is passed through
            $discount = $this->maxDiscount($this->settings->max_discount_value, $discount, false);
        }


        return $discount;
    }

    /**
     * @param null $locale
     * @param null $internationalFormat
     * @param bool $format
     * @return mixed|string
     * @throws CouponException
     */
    public function displayValue($locale = null, $internationalFormat = null, $format = true)
    {
        if ($this->settings->type == "fixed") {
            return \Currency::format($this->discount());
        } else {
            return ($this->settings->value * 100) . '%';
        }

    }
}
