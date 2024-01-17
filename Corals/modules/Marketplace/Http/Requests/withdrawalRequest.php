<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;

class withdrawalRequest extends BaseRequest
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


        $transactionSummary = \Store::getTransactionsSummary();


        $rules = [
            'notes' => 'required',
            'amount' => 'required|numeric|max:' . $transactionSummary['balance']
        ];


        $rules = \Filters::do_filter('withdrawal_request_rules', $rules, request());

        return $rules;
    }


}
