<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Brand;

class BrandRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Brand::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Brand::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'status' => 'required',
                'thumbnail' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:191|unique:marketplace_brands,name',
                'slug' => 'required|max:191|unique:marketplace_brands,slug'
            ]);
        }

        if ($this->isUpdate()) {
            $brand = $this->route('brand');
            $rules = array_merge($rules, [
                'name' => 'required|max:191|unique:marketplace_brands,name,' . $brand->id,
                'slug' => 'required|max:191|unique:marketplace_brands,slug,' . $brand->id,
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

        if (isset($data['slug'])) {
            $data['slug'] = \Str::slug($data['slug']);
        }

        $data['is_featured'] = \Arr::get($data, 'is_featured', false);

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
