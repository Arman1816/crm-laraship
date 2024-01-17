<?php

namespace Corals\Modules\Payment\Common\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Payment\Common\Models\TaxClass;

class TaxClassRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(TaxClass::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(TaxClass::class);
        $rules = parent::rules();


        if ($this->isStore()) {
            $rules = array_merge($rules, [
                    'name' => 'required|max:191|unique:tax_classes,name',
                ]
            );
        }

        if ($this->isUpdate()) {
            $tax_class = $this->route('role');

            $rules = array_merge($rules, [
                    'name' => 'required|max:191|unique:tax_classes,name,' . $tax_class->id,
                ]
            );
        }


        return $rules;
    }

}
