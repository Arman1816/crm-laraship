<?php

namespace Corals\Modules\Utility\Classes\Category;

use Corals\Foundation\Facades\CoralsForm;
use Corals\Modules\Utility\Models\Category\Attribute;
use Corals\Modules\Utility\Models\Category\AttributeOption;
use Corals\Modules\Utility\Models\Category\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryManager
{

    public function getCategoriesList(
        $module = null,
        $parentsOnly = false,
        $objects = false,
        $status = null,
        $except = [],
        $featured = false,
        $orderBy = 'name ASC'
    ) {
        $categories = Category::query();

        if ($module) {
            $categories = $categories->where('module', $module);
        }

        if ($status) {
            $categories = $categories->where('status', $status);
        }

        if (!empty($except)) {
            $categories = $categories->whereNotIn('id', $except);
        }

        if ($featured) {
            $categories = $categories->featured();
        }

        if ($parentsOnly) {
            $categories = $categories->where(function ($parentQuery) {
                $parentQuery->whereNull('parent_id')
                    ->orWhere('parent_id', 0);
            });

            if ($objects) {
                $categories = $categories->get();
            } else {
                $categories = $categories->pluck('name', 'id')->toArray();
            }

            return $categories;
        }

        $categories = $categories->orderByRaw($orderBy);

        if ($objects) {
            return $categories->get();
        } else {
            $categories = $categories->get();

            $categoriesResult = [];

            foreach ($categories as $category) {
                $categoriesResult = $this->appendCategory($categoriesResult, $category);
            }

            return $categoriesResult;
        }
    }

    public function getCategoriesByParent($parent, $status = 'active', $objects = false)
    {
        if (!($parent instanceof Category)) {
            if (is_int($parent)) {
                $parent = Category::query()->find($parent);
            } else {
                $parent = Category::query()->where('slug', $parent)->first();
            }
        }
        if (!$parent) {
            return [];
        }

        $categories = $parent->children();

        if ($status) {
            $categories->where('status', $status);
        }

        if ($objects) {
            return $categories->get();
        }

        return $categories->pluck('name', 'id')->toArray();
    }

    /**
     * @param $categories
     * @param $category
     * @param bool $isAChild
     * @return mixed
     */
    protected function appendCategory($categories, $category, $isAChild = false)
    {
        if ($category->hasChildren()) {
            $categories[$category->name] = [];
            foreach ($category->children as $child) {
                $categories[$category->name] = $this->appendCategory($categories[$category->name], $child, true);
            }
        } elseif ($isAChild || $category->isRoot()) {
            $categories[$category->id] = $category->name;
        }

        return $categories;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAttributesList()
    {
        $attributes = Attribute::all()->pluck('label', 'id');

        return $attributes;
    }

    /**
     * @return array
     */
    public function attributesColumnMapping()
    {
        $attributes = Attribute::query()->where('use_as_filter', true)->get();

        $attributesColumnMapping = [];

        foreach ($attributes as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                case 'text':
                case 'date':
                    $attributesColumnMapping[$attribute->id]['column'] = 'string_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = 'like';
                    break;
                case 'textarea':
                    $attributesColumnMapping[$attribute->id]['column'] = 'text_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = 'like';
                    break;
                case 'number':
                case 'select':
                case 'multi_values':
                case 'radio':
                    $attributesColumnMapping[$attribute->id]['column'] = 'number_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = '=';
                    break;
                default:
                    $attributesColumnMapping[$attribute->id]['column'] = 'string_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = '=';
            }
        }

        return $attributesColumnMapping;
    }


    /**
     * @param $field
     * @param null $product
     * @param array $attributes
     * @param bool $asForm
     * @return array|string
     */
    public function renderAttribute($field, $product = null, $attributes = [], $asForm = true)
    {
        $value = null;

        $asFilter = \Arr::pull($attributes, 'as_filter', false);


        if ($product) {
            $options = $product->options_all()->where('attribute_id', $field->id)->get();
            if ($options->count() > 1) {
                // in case of multiple type
                $value = AttributeOption::whereIn('id', $options->pluck('number_value')->toArray())
                    ->pluck('id')->toArray();
            } elseif ($option = $options->first()) {
                $value = optional($option)->value;
            }
        }

        $input = '';

        switch ($field->type) {
            case 'number':
            case 'date':
            case 'text':
            case 'textarea':
                if ($field->type == 'number') {
                    $attributes = array_merge(['step' => '0.01'], $attributes);
                }
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label,
                    $asFilter ? false : $field->required, $value, $attributes);
                break;
            case 'checkbox':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label, $value, 1,
                    $attributes);
                $value = yesNoFormatter($value);
                break;
            case 'radio':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label,
                    $asFilter ? false : $field->required, $field->options->pluck('option_display', 'id')->toArray(),
                    $value, $attributes);
                $options = $field->options->pluck('option_display', 'id')->toArray();

                $value = $this->getValue($value, $options);
                break;
            case 'select':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label,
                    $field->options->pluck('option_display', 'id')->toArray(), $asFilter ? false : $field->required,
                    $value, $attributes, 'select2');
                $options = $field->options->pluck('option_display', 'id')->toArray();
                $value = $this->getValue($value, $options);
                break;
            case 'multi_values':

                $attributes = array_merge(['class' => 'select2-normal', 'multiple' => true], $attributes);
                $input = CoralsForm::select('options[' . $field->id . '][]', $field->label,
                    $field->options->pluck('option_display', 'id')->toArray(), $asFilter ? false : $field->required,
                    $value, $attributes, 'select2');

                $options = $field->options->pluck('option_display', 'id')->toArray();

                $value = $this->getValue($value, $options);

                break;
            case 'color':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label,
                    $asFilter ? false : $field->required, $value, $attributes);
                $value = "<div style=\"display:inline-block;background-color:{$value};height: 100%;width: 25px;\">&nbsp;</div>";
                break;
        }

        if (!$asForm) {
            return [
                $field->label => $value
            ];
        }

        return $input;
    }

    public function setModelOptions($request, Model $model, $requestParameter = 'options')
    {
        $options = [];

        $requestHasOptions = $request->has($requestParameter);

        $model->options()->forceDelete();

        if ($requestHasOptions) {
            foreach ($request->get($requestParameter, []) ?? [] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_option) {
                        $options[] = [
                            'attribute_id' => $key,
                            'value' => $value_option,
                        ];
                    }
                } else {
                    $options[] = [
                        'attribute_id' => $key,
                        'value' => $value,
                    ];
                }
            }

            $model->options()->createMany($options);
        }
    }

    /**
     * @param $value
     * @param array $options
     * @return mixed|string|null
     */
    protected function getValue($value, $options = [])
    {
        if (is_null($value)) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $v) {
                $result[] = $options[$v];
            }

            $value = join(", ", $result ?? []);
        } else {
            $value = $options[$value];
        }

        return $value;
    }
}
