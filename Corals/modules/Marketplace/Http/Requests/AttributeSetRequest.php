<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\AttributeSet;
use Illuminate\Support\Str;

class AttributeSetRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(AttributeSet::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(AttributeSet::class);

        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:marketplace_attribute_sets,code',
                'name' => 'required|max:191|unique:marketplace_attribute_sets,name',
            ]);
        }

        if ($this->isUpdate()) {
            $attributeSet = $this->route('attribute_set');

            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:marketplace_attribute_sets,code,' . $attributeSet->id,
                'name' => 'required|max:191|unique:marketplace_attribute_sets,name,' . $attributeSet->id,
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

            $data['is_default'] = data_get($data, 'is_default', false);
            $data['code'] = Str::slug(data_get($data, 'code', false));

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
