<?php

namespace Corals\Modules\Subscriptions\Http\Requests\API;

use \Corals\Modules\Subscriptions\Http\Requests\SubscriptionCheckoutRequest as ParentSubscriptionCheckoutRequest;

class SubscriptionCheckoutRequest extends ParentSubscriptionCheckoutRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['checkoutToken']);

        $rules['integration_id'] = 'required';

        return $rules;
    }
}
