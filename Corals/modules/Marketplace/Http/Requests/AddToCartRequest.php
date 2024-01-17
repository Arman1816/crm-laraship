<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;

class AddToCartRequest extends BaseRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $sku = request()->route('sku');

        if (!$sku) {
            $rules = ['sku_hash' => 'required'];
        }

        $rules = \Filters::do_filter('add_to_cart_request_rules', $rules, request());

        return $rules;
    }

    public function attributes()
    {
        $attributes = ['sku_hash' => 'SKU'];

        return $attributes;
    }


}
