<?php

namespace Corals\Modules\Marketplace\Traits;

use Carbon\Carbon;
use Corals\Modules\Marketplace\Classes\CartItem;
use Corals\Modules\Marketplace\Exceptions\CouponException;
use Corals\Modules\Marketplace\Exceptions\InvalidPrice;
use Corals\Modules\Marketplace\Classes\ShoppingCart;

/**
 * Class CouponTrait.
 */
trait CouponTrait
{
    /**
     * @var bool
     */
    public $appliedToCart = true;

    use CartOptionsMagicMethodsTrait;

    /**
     * Sets all the options for the coupon.
     *
     * @param $options
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Checks to see if we can apply the coupon.
     *
     * @return bool
     */
    public function canApply()
    {
        try {
            $this->discount(true);

            return true;
        } catch (CouponException $e) {
            return false;
        }
    }

    /**
     * Get the reason why a coupon has failed to apply.
     *
     * @deprecated 1.3
     *
     * @return string
     */
    public function getMessage()
    {
        try {
            $this->discount(true);

            return config('shoppingcart.coupon_applied_message', 'Coupon Applied');
        } catch (CouponException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Gets the failed message for a coupon.
     *
     * @return null|string
     */
    public function getFailedMessage()
    {
        try {
            $this->discount(true);
        } catch (CouponException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Checks the minimum subtotal needed to apply the coupon.
     *
     * @param $minAmount
     * @param $throwErrors
     *
     * @throws CouponException
     *
     * @return bool
     */
    public function checkMinAmount($store_id, $minAmount, $throwErrors = true)
    {
        $shoppingCart = \App::make(ShoppingCart::SERVICE);
        if ($store_id) {
            $shoppingCart->setInstance($store_id);
            $cart_total = $shoppingCart->subTotal(false, false);
        } else {
            $cart_total = $shoppingCart->subTotalAllInstances(false,false);
        }

        if ($cart_total >= $minAmount) {
            return true;
        } else {
            if ($throwErrors) {
                throw new CouponException(trans('Marketplace::exception.coupon.must_least_total', ['amount' => $shoppingCart->formatMoney($minAmount)]));
            } else {
                return false;
            }
        }
    }


    /**
     * Returns either the max discount or the discount applied based on what is passed through.
     *
     * @param $maxDiscount
     * @param $discount
     * @param $throwErrors
     *
     * @throws CouponException
     *
     * @return mixed
     */
    public function maxDiscount($maxDiscount, $discount, $throwErrors = true)
    {
        $shoppingCart = \App::make(ShoppingCart::SERVICE);

        if ($maxDiscount == 0 || $maxDiscount > $discount) {
            return $discount;
        } else {
            if ($throwErrors) {
                throw new CouponException(trans('Marketplace::exception.coupon.max_discount_amount', ['amount' => $shoppingCart->formatMoney($maxDiscount)]));
            } else {
                return $maxDiscount;
            }
        }
    }

    /**
     * Checks to see if the times are valid for the coupon.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param $throwErrors
     *
     * @throws CouponException
     *
     * @return bool
     */
    public function checkValidTimes(Carbon $startDate, Carbon $endDate, $throwErrors = true)
    {
        if (Carbon::now()->between($startDate, $endDate)) {
            return true;
        } else {
            if ($throwErrors) {
                throw new CouponException(trans('Marketplace::exception.coupon.coupon_not_available'));
            } else {
                return false;
            }
        }
    }

    /**
     * Sets a discount to an item with what code was used and the discount amount.
     *
     * @param CartItem $item
     * @param $discountAmount
     *
     * @throws InvalidPrice
     */
    public function setDiscountOnItem(CartItem $item, $discountAmount)
    {
        if (!is_numeric($discountAmount)) {
            throw new InvalidPrice(trans('Marketplace::exception.coupon.must_use_discount_amount'));
        }
        $this->appliedToCart = false;
        $item->code = $this->code;
        $item->discount = $discountAmount;
        $item->couponInfo = $this->options;
    }
}
