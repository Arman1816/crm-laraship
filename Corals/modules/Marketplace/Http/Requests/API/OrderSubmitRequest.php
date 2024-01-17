<?php

namespace Corals\Modules\Marketplace\Http\Requests\API;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Classes\Coupons\Advanced;
use Corals\Modules\Marketplace\Models\Coupon;

class OrderSubmitRequest extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     * @throws \Corals\Modules\Marketplace\Exceptions\CouponException
     */
    public function rules()
    {
        $rules = [
            'orders' => 'required',
            'orders.*.order_items' => 'required',
            'orders.*.amount' => 'required|numeric',
            'orders.*.store_id' => 'required|exists:marketplace_stores,id',
            'orders.*.order_items.*.amount' => 'required',
            'orders.*.order_items.*.quantity' => 'required',
            'orders.*.order_items.*.description' => 'required',
            'orders.*.order_items.*.type' => 'required|in:Product,Discount,Tax,Shipping',
            'orders.*.order_items.*.tax_ids' => 'nullable|array',
            'orders.*.order_items.*.properties' => 'nullable|array',
            'status' => 'required|in:processing,submitted',
            'payment_reference' => 'required',
            'gateway' => 'required',
            'payment_status' => 'required|in:pending,paid',
            'billing_address.first_name' => 'required',
            'billing_address.last_name' => 'required',
            'billing_address.email' => 'required',
            'billing_address.address_1' => 'required',
            'billing_address.city' => 'required',
            'billing_address.state' => 'required',
            'billing_address.country' => 'required',
            'billing_address.zip' => 'required',
        ];

        if ($this->get('enable_shipping')) {
            $rules = array_merge($rules, [
                'shipping_address.first_name' => 'required',
                'shipping_address.last_name' => 'required',
                'shipping_address.address_1' => 'required',
                'shipping_address.city' => 'required',
                'shipping_address.state' => 'required',
                'shipping_address.country' => 'required',
                'shipping_address.zip' => 'required',
            ]);
        }

        foreach ($this->get('orders', []) ?? [] as $order) {
            foreach ($order['order_items'] ?? [] as $item) {
                if ($item['type'] === 'Discount') {
                    $code = $item['sku_code'];

                    $coupon = Coupon::where('code', $code)->first();

                    if (!$coupon) {
                        throw new \Exception(trans('Marketplace::exception.checkout.invalid_coupon', ['code' => $code]));
                    }

                    $coupon_class = new Advanced($code, $coupon, []);

                    $coupon_class->validate(true);
                }
            }
        }

        return $rules;
    }
}
