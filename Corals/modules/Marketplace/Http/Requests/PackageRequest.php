<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Package;

class PackageRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Package::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Package::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required',
                'length' => 'required',
                'width' => 'required',
                'height' => 'required',
                'distance_unit' => 'required',
                'weight' => 'required',
                'mass_unit' => 'required',
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isStore() || $this->isUpdate()) {
            $data = $this->all();

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
