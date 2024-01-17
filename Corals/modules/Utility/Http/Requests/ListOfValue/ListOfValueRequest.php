<?php

namespace Corals\Modules\Utility\Http\Requests\ListOfValue;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Illuminate\Support\Arr;

class ListOfValueRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(ListOfValue::class, 'list_of_value');

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(ListOfValue::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'status' => 'required',
                'value' => 'required',
                'display_order' => 'required',
                'properties.*.key' => 'required',
                'properties.*.value' => 'required',
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'code' => 'nullable|max:191|unique:utility_list_of_values,code'
            ]);
        }

        if ($this->isUpdate()) {
            $listOfValue = $this->route('list_of_value');

            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:utility_list_of_values,code,' . $listOfValue->id,
            ]);
        }

        return $rules;
    }

    public function getValidatorInstance()
    {
        if ($this->isUpdate()) {
            $data = $this->all();

            $data['hidden'] = Arr::get($data, 'hidden', false);
            $data['display_order'] = Arr::get($data, 'display_order', 0);
            $data['properties'] = Arr::get($data, 'properties', []);

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('properties', []) ?? [] as $index => $solution) {
            $attributes["properties.*.key"] = trans("Corals::labels.key");
            $attributes["properties.*.value"] = trans("Corals::labels.value");
        }

        return $attributes;
    }
}
