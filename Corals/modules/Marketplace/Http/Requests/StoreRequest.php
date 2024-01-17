<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Store;

class StoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Store::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Store::class);

        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required',
                'parking_domain' => 'max:191',
            ]);

            if (\Store::isStoreAdmin()) {
                $rules = array_merge($rules, [
                    'user_id' => 'required',
                    'custom_commission'=>'nullable|numeric|between:0,100'
                ]);
            }
        }


        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'slug' => 'unique:marketplace_stores,slug',
                'code' => 'nullable|max:3|unique:marketplace_stores,code',
            ]);
        }

        if ($this->isUpdate()) {
            $store = $this->route('store');
            $rules = array_merge($rules, [
                'slug' => 'unique:marketplace_stores,slug,' . $store->id,
                'code' => 'nullable|max:3|unique:marketplace_stores,code,' . $store->id,
            ]);

            $rules = array_merge($rules, [
            ]);
        }

        return $rules;
    }
}
