<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Attribute;

class AttributeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Attribute::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Attribute::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'type' => 'required|max:191',
                'label' => 'required|max:191',
                'display_order' => 'required|numeric',
            ]);

            foreach ($this->get('options', []) as $id => $item) {
                $rules = array_merge($rules, [
                    "options.{$id}.option_value" => 'required',
                    "options.{$id}.option_order" => 'required|numeric',
                    "options.{$id}.option_display" => 'required',
                ]);

                if (data_get($this, 'properties.display_type') == 'image') {
                    if (isset($item['id'])) {
                        $rules["options.{$id}.option_display"] = 'nullable|image|max:' . maxUploadFileSize();
                    } else {
                        $rules["options.{$id}.option_display"] = 'required|image|max:' . maxUploadFileSize();
                    }
                }
            }
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:marketplace_attributes,code'
            ]);
        }

        if ($this->isUpdate()) {
            $attribute = $this->route('attribute');

            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:marketplace_attributes,code,' . $attribute->id,
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();

        $data['required'] = $this->get('required', false);

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
