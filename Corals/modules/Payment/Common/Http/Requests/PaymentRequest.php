<?php

namespace Corals\Modules\Payment\Common\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;

class PaymentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (user()->hasPermissionTo('Administrations::admin.payment')) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
