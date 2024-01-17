<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\SKU;

class SKUBulkRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(SKU::class, 'sku');

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(SKU::class, 'sku');

        $rules = parent::rules();

        $product = request()->route('product');

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'generate_option' => 'required',
            ]);

            foreach ($product->variation_options ?? [] as $option) {
                if ($option->required) {
                    $rules = array_merge($rules, [
                        "options." . $option->id => 'required',
                    ]);
                }
            }
        }

        if ($this->isUpdate()) {
            foreach ($this->get('sku', []) ?? [] as $id => $sku) {
                $rules = array_merge($rules, [
                    "sku.$id.code" => 'required|unique:marketplace_sku,code,' . $id
                ]);
            }

            if ($this->get('generate_option') === 'apply_unique') {
                $rules = array_merge($rules, [
                    'sku.*.status' => 'required',
                    'sku.*.regular_price' => 'required',
                    'sku.*.image' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
                    'sku.*.inventory' => 'required',
                    'sku.*.inventory_value' => 'required_if:sku.*.inventory,finite,bucket',
                ]);

//                if ($product->shipping['enabled']) {
//                    $rules = array_merge($rules, [
//                        "sku.*.shipping.height" => 'required',
//                        "sku.*.shipping.weight" => 'required',
//                        "sku.*.shipping.width" => 'required',
//                        "sku.*.shipping.length" => 'required',
//                    ]);
//                }
            } else {
                $rules = array_merge($rules, [
                    'status' => 'required',
                    'regular_price' => 'required',
                    'image' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
                    'inventory' => 'required',
                    'inventory_value' => 'required_if:inventory,finite,bucket',
                ]);
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];
        $product = request()->route('product');

        foreach ($product->variation_options ?? [] as $option) {
            $attributes = array_merge($attributes, [
                "options.$option->id" => $option->label,
            ]);
        }

        $attributes = array_merge($attributes, [
            'sku.*.status' => __('Corals::attributes.status'),
            'status' => __('Corals::attributes.status'),

            'sku.*.regular_price' => __('Marketplace::attributes.sku.regular_price'),
            'regular_price' => __('Marketplace::attributes.sku.regular_price'),

            'sku.*.image' => __('Marketplace::attributes.sku.image'),
            'image' => __('Marketplace::attributes.sku.image'),

            'sku.*.inventory' => __('Marketplace::attributes.sku.inventory'),
            'inventory' => __('Marketplace::attributes.sku.inventory'),

            'sku.*.inventory_value' => __('Marketplace::attributes.sku.inventory_value'),
            'inventory_value' => __('Marketplace::attributes.sku.inventory_value'),

            "sku.*.shipping.height" => __('Marketplace::attributes.sku.height'),
            "sku.*.shipping.weight" => __('Marketplace::attributes.sku.weight'),
            "sku.*.shipping.width" => __('Marketplace::attributes.sku.width'),
            "sku.*.shipping.length" => __('Marketplace::attributes.sku.length'),
        ]);

        foreach ($this->get('sku', []) ?? [] as $id => $sku) {
            $attributes = array_merge($attributes, [
                "sku.$id.code" => __('Marketplace::attributes.sku.code_sku')
            ]);
        }

        return $attributes;
    }
}
