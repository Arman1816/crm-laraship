<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\AttributeSet;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Traits\DownloadableRequest;

class ProductRequest extends BaseRequest
{
    use DownloadableRequest;

    /**
     * @var mixed
     */
    public $setAttributes = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Product::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Product::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:191',
                'caption' => 'required',
                'status' => 'required',
                'type' => 'required|in:simple,variable',
                'inventory' => 'required_if:type,simple',
                'regular_price' => 'required_if:type,simple',
                'code' => 'required_if:type,simple',
                'product_code' => 'required_if:type,variable',
                'variation_options' => 'required_if:type,variable',
                'categories' => 'required',
                'shipping.shipping_option' => 'required_with_all:shipping.enabled,code',


            ]);

            switch ($this->get('bulk_price')) {
                case 'offers':
                    $bulkPriceRules = [
                        'offers.*.type' => 'required',
                        'offers.*.quantity' => 'required|min:1',
                        'offers.*.value' => 'required|min:1'
                    ];
                    break;
                case 'price_per_quantity':
                    $bulkPriceRules = [
                        'price_per_quantity.*.min_quantity' => 'required|min:1|lte:price_per_quantity.*.max_quantity',
                        'price_per_quantity.*.max_quantity' => 'required|min:1|gte:price_per_quantity.*.min_quantity',
                        'price_per_quantity.*.price' => 'required',
                        'price_per_quantity.*.description' => 'required',
                        'price_per_quantity.*.type' => 'required',
                    ];
                    break;

            }

            $rules = array_merge($rules, $bulkPriceRules ?? []);

            if ($this->input('shipping.shipping_option') == 'flat_rate_prices') {
                $rules = array_merge($rules, [
                    'shipping_rate' => 'required',
                    'shipping_rate.*.name' => 'required',
                    'shipping_rate.*.country' => 'required',
                    'shipping_rate.*.shipping_provider' => 'required',
                    'shipping_rate.*.shipping_method' => 'required',
                    'shipping_rate.*.one_item_price' => 'required_if:shipping_rate.*.shipping_method,FlatRate',
                ]);
            } else {
                $rules = array_merge($rules, [
//                    'shipping.width' => 'required_with_all:shipping.enabled,code',
//                    'shipping.height' => 'required_with_all:shipping.enabled,code',
//                    'shipping.length' => 'required_with_all:shipping.enabled,code',
//                    'shipping.weight' => 'required_with_all:shipping.enabled,code',
                ]);
            }
            if ($this->input('type') == 'simple' && in_array($this->input('type'), ['finite', 'bucket'])) {
                $rules['inventory_value'] = 'required';
            }

            if ($this->input('price_per_classification')) {
                foreach (\Settings::get('customer_classifications', []) as $key => $value) {
                    $classification_rules['classification_price.' . $key] = 'nullable|min:0|not_in:0';
                }
                $rules = array_merge($rules, $classification_rules);
            }

            $attributeSets = $this->get('attribute_sets', []);

            $attributeSets = AttributeSet::query()->whereIn('id', $attributeSets)->get();

            $attributes = collect([]);

            foreach ($attributeSets as $attributeSet) {
                $attributes = $attributes->merge($attributeSet->productAttributes);
            }

            $this->setAttributes = $attributes;

            foreach ($attributes as $attribute) {
                if (in_array($attribute->id, $this->get('variation_options', []))) {
                    continue;
                }
                if ($attribute->required) {
                    $rules = array_merge($rules, [
                        "set_attribute_options." . $attribute->id => 'required',
                    ]);
                }
            }
        }

        if ($this->isStore()) {
            $rules = $this->downloadableStoreRules($rules);

            $rules = array_merge($rules, [
                'slug' => 'max:191|unique:marketplace_products,slug'
            ]);
        }

        if ($this->isUpdate()) {
            $product = $this->route('product');

            $rules = $this->downloadableUpdateRules($rules, $product);

            $rules = array_merge($rules, [
                'slug' => 'max:191|unique:marketplace_products,slug,' . $product->id,
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        if ($this->isStore() || $this->isUpdate()) {
            $attributes ['product_code'] = trans('Marketplace::attributes.product.product_code');

            $attributes = [
                'shipping.shipping_option' => 'shipping option',
                'shipping.enabled' => 'shipping enabled',
                'shipping.width' => 'width',
                'shipping.height' => 'height',
                'shipping.length' => 'length',
                'shipping.weight' => 'weight',
                'code' => 'SKU code'
            ];

            foreach (\Settings::get('customer_classifications', []) as $key => $value) {
                $attributes['classification_price.' . $key] = $key;
            }

            foreach ($this->get('price_per_quantity', []) as $index => $value) {
                $attributes["price_per_quantity.$index.min_quantity"] = trans('Marketplace::attributes.product.price_per_quantity.min_qty');
                $attributes["price_per_quantity.$index.max_quantity"] = trans('Marketplace::attributes.product.price_per_quantity.max_qty');
                $attributes["price_per_quantity.$index.price"] = trans('Marketplace::attributes.product.price_per_quantity.price');
                $attributes["price_per_quantity.$index.type"] = trans('Marketplace::attributes.product.price_per_quantity.type');
                $attributes["price_per_quantity.$index.description"] = trans('Marketplace::attributes.product.price_per_quantity.description');
            }

            foreach ($this->get('offers', []) as $index => $value) {
                $attributes["offers.$index.type"] = trans('Marketplace::attributes.product.offers.type');
                $attributes["offers.$index.quantity"] = trans('Marketplace::attributes.product.offers.quantity');

                $key = 'price';

                if (data_get($value, 'type') == 'get_free') {
                    $key = 'free_items';
                }

                $attributes["offers.$index.value"] = strtolower(trans("Marketplace::attributes.product.offers.$key"));
            }

            foreach ($this->get('shipping_rate', []) ?? [] as $index => $rate) {
                $attributes["shipping_rate.$index.name"] = 'name';
                $attributes["shipping_rate.$index.country"] = 'country';
                $attributes["shipping_rate.$index.shipping_provider"] = 'provider';
                $attributes["shipping_rate.$index.one_item_price"] = 'price';
                $attributes["shipping_rate.$index.shipping_method"] = 'shipping method';
            }

            $attributes = $this->downloadableAttributes($attributes);

            $options = $this->setAttributes;

            foreach ($options as $option) {
                $attributes = array_merge($attributes, [
                    "set_attribute_options.$option->id" => $option->label,
                ]);
            }
        }

        return $attributes;
    }

    public function messages()
    {
        $messages['shipping_rate.required'] = trans('Marketplace::messages.product.at_least_one_rate');

        $messages["shipping_rate.*.one_item_price.required_if"] = trans('validation.required',
            ['attribute' => 'price']);

        return $this->downloadableMessages($messages);
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isStore() || $this->isUpdate()) {
            $data = $this->all();

            if (isset($data['slug'])) {
                $data['slug'] = \Str::slug($data['slug']);
            }

            $data['is_featured'] = \Arr::get($data, 'is_featured', false);

            $data['properties'] = \Arr::get($data, 'properties', []);

            if ($this->isUpdate()) {
                $product = $this->route('product');
                $data['properties'] = array_merge($product->properties ?? [], $data['properties']);
            }

            if (data_get($data, 'shipping.shipping_option') == 'flat_rate_prices') {
                foreach (
                    [
                        'width',
                        'height',
                        'length',
                        'weight'
                    ] as $key
                ) {
                    unset($data['shipping'][$key]);
                }

                foreach ($this->get('shipping_rate', []) ?? [] as $index => $rate) {
                    data_set($data, "shipping_rate.$index.name",
                        data_get($data, "shipping_rate.$index.shipping_method"));
                }
            }

            foreach (data_get($data, 'variation_options', []) as $option) {
                unset($data['set_attribute_options'][$option]);
            }

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
