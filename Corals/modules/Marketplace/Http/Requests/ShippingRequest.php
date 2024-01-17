<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Shipping;

class ShippingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Shipping::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Shipping::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required',
                'shipping_method' => 'required',
                'priority' => 'required|numeric',
                'rate' => 'numeric|required_if:shipping_method,FlatRate',
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isUpdate() || $this->isStore()) {
            $data = $this->all();

            $data['exclusive'] = data_get($data, 'exclusive', false);

            $shippingMethod = data_get($data, 'shipping_method');

            if ($shippingMethod == 'FlatRate') {
                $cart_level = data_get($data, 'properties.cart_level', 0);
            } else {
                $cart_level = 0;
            }

            data_set($data, 'properties.cart_level', $cart_level);

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }


}
