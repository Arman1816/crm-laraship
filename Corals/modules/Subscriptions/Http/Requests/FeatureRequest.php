<?php

namespace Corals\Modules\Subscriptions\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Subscriptions\Models\Feature;
use Illuminate\Support\Arr;

class FeatureRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Feature::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Feature::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'caption' => 'required|max:191',
                'status' => 'required',
                'type' => 'required',
                'description' => 'required',
                'extras.code' => 'required_if:type,config',
                'extras.source' => 'required_if:type,config',
            ]);
        }

        if ($this->isStore()) {
            $product = $this->route('product');

            $rules = array_merge($rules, [
                'name' => 'required|max:191|unique:features,name,null,id,product_id,' . $product->id
            ]);
        }

        if ($this->isUpdate()) {
            $product = $this->route('product');
            $feature = $this->route('feature');

            $rules = array_merge($rules, [
                'name' => "required|max:191|unique:features,name,{$feature->id},id,product_id,{$product->id}",
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

            if ($this->get('type') !== 'config') {
                Arr::forget($data, 'extras.config');
            }

            $data['is_visible'] = \Arr::get($data, 'is_visible', false);
            $data['per_cycle'] = \Arr::get($data, 'per_cycle', false);

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'extras.code' => 'code',
            'extras.source' => 'source',
        ];
    }
}
