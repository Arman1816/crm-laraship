<?php

namespace Corals\Modules\Subscriptions\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Subscriptions\Models\PlanUsage;

class PlanUsageRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(PlanUsage::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {

            $rules = array_merge($rules, [
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
            ]);
        }

        if ($this->isUpdate()) {
            $planUsage = $this->route('plan_usage');

            $rules = array_merge($rules, [
            ]);
        }

        return $rules;
    }
}
