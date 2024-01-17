<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Coupon;

class CouponTransformer extends APIBaseTransformer
{
    /**
     * @param Coupon $coupon
     * @return array
     * @throws \Throwable
     */
    public function transform(Coupon $coupon)
    {
        $coupon_status = $coupon->status();

        if ($coupon_status == "active") {
            $status = trans('Marketplace::attributes.coupon.status_options.active');
        } else if ($coupon_status == "pending") {
            $status = trans('Marketplace::attributes.coupon.status_options.pending');
        } else {
            $status = trans('Marketplace::attributes.coupon.status_options.expired');
        }

        $productsList = apiPluck($coupon->products()->pluck('name', 'id'));
        $usersList = apiPluck($coupon->users()->pluck('email', 'id'));

        $transformedArray = [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'usage_count' => $coupon->uses,
            'min_cart_total' => $coupon->min_cart_total ? \Payments::currency($coupon->min_cart_total) : null,
            'max_discount_value' => $coupon->max_discount_value ? \Payments::currency($coupon->max_discount_value) : null,
            'value' => $coupon->type == "percentage" ? $coupon->value . "%" : \Currency::format($coupon->value, \Payments::admin_currency_code()),
            'parent_id' => optional($coupon->parent)->name,
            'store_id' => $coupon->store_id,
            'store' => $coupon->store ? $coupon->store->name : null,
            'type' => ucfirst($coupon->type),
            'start' => format_date($coupon->start),
            'expiry' => format_date($coupon->expiry),
            'status' => $status,
            'products' => $productsList,
            'users' => $usersList,
        ];

        return parent::transformResponse($transformedArray);
    }
}
