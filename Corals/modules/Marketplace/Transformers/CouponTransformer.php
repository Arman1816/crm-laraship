<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Coupon;

class CouponTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.coupon.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Coupon $coupon
     * @return array
     * @throws \Throwable
     */
    public function transform(Coupon $coupon)
    {
        $coupon_status = $coupon->status();
        if ($coupon_status == "active") {
            $status = '<span class="label label-success">' . trans('Marketplace::attributes.coupon.status_options.active') . '</span>';
        } else if ($coupon_status == "pending") {
            $status = '<span class="label label-info">' . trans('Marketplace::attributes.coupon.status_options.pending') . '</span>';

        } else {
            $status = '<span class="label label-warning">' . trans('Marketplace::attributes.coupon.status_options.expired') . '</span>';
        }

        $transformedArray = [
            'id' => $coupon->id,
            'checkbox' => $this->generateCheckboxElement($coupon),
            'code' => $coupon->code,
            'value' => $coupon->type == "percentage" ? $coupon->value . "%" : \Currency::format($coupon->value, \Payments::admin_currency_code()),
            'parent_id' => optional($coupon->parent)->name ?? '-',
            'type' => ucfirst($coupon->type),
            'store' => $coupon->store ? $coupon->store->name : '-',
            'start' => format_date($coupon->start),
            'status' => $status,
            'expiry' => format_date($coupon->expiry),
            'action' => $this->actions($coupon)
        ];

        return parent::transformResponse($transformedArray);
    }
}
