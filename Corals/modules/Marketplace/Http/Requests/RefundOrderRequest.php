<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Order;

class RefundOrderRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->hasPermissionTo('Marketplace::order.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Order::class);

        $order = $this->route('order');


        if ($this->is('*do-refund')) {
            $rules = parent::rules();
            $rules = array_merge($rules, [
                'amount' => 'required|gt:0|max:' . ($order->amount - $order->getPaymentRefundedAmount()),
                'type' => 'required'
            ]);
            return $rules;
        }
        return [];
    }

    public function messages()
    {
        return [
            'amount.gt' => trans('Marketplace::messages.validation.greater_than_zero'),
        ];
    }
}
