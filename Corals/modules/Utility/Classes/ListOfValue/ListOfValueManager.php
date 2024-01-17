<?php

namespace Corals\Modules\Utility\Classes\ListOfValue;

use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Illuminate\Support\Str;

class ListOfValueManager
{
    /**
     * @param null $module
     * @param bool $objects
     * @param null $status
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getParents($module = null, $objects = false, $status = null)
    {
        $listOfValues = ListOfValue::query()->whereNull('parent_id');

        if (!is_null($module)) {
            $listOfValues = $listOfValues->withModule($module);
        }

        if ($status) {
            $listOfValues = $listOfValues->where('status', $status);
        }

        $listOfValues = $listOfValues->orderBy('display_order')->get();

        if ($objects) {
            return $listOfValues;
        }

        $listOfValuesList = [];

        foreach ($listOfValues as $listOfValue) {
            $listOfValuesList [$listOfValue->id] = sprintf("%s %s", Str::limit($listOfValue->value, 30), $listOfValue->module ?: '');
        }

        return $listOfValuesList;
    }

    /**
     * @param $parentCode
     * @param bool $useCode
     * @param string $status
     * @param bool $objects
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get($parentCode, $objects = false, $status = 'active', $useCode = true)
    {
        $listOfValues = ListOfValue::query()
            ->join('utility_list_of_values as parent', 'parent.id', 'utility_list_of_values.parent_id')
            ->where('parent.code', $parentCode)
            ->select('utility_list_of_values.*');

        if ($status) {
            $listOfValues = $listOfValues->where('utility_list_of_values.status', $status);
        }

        $listOfValues = $listOfValues->orderBy('display_order')->get();

        if ($objects) {
            return $listOfValues;
        }

        $listOfValuesList = [];

        $codeColumn = $useCode ? 'code' : 'id';

        foreach ($listOfValues as $listOfValue) {
            $listOfValuesList [$listOfValue->{$codeColumn}] = $listOfValue->label ?? $listOfValue->value;
        }

        return $listOfValuesList;
    }

    /**
     * @param $code
     * @param null $parentCode
     * @param string $attribute
     * @param null $default
     * @return mixed|string|null
     */
    public function getColoredLOVByCode($code, $parentCode = null, $attribute = 'label', $default = null)
    {
        $listOfValue = $this->getLOVByCode($code, $parentCode, true, $default, $attribute);

        $value = optional($listOfValue)->{$attribute} ?? $default;

        if ($value) {
            $color = $listOfValue->getProperty('color');

            $labelConfig = $color ? ['level' => $color] : [];

            $value = formatStatusAsLabels($value, $labelConfig);
        }

        return $value;
    }

    /**
     * @param $code
     * @param null $parentCode
     * @param string $attribute
     * @param null $default
     * @param bool $object
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public function getLOVByCode($code, $parentCode = null, $object = false, $default = null, $attribute = 'label')
    {
        $listOfValue = ListOfValue::query();

        if ($parentCode) {
            $listOfValue->join('utility_list_of_values as parent', 'parent.id', 'utility_list_of_values.parent_id')
                ->where('parent.code', $parentCode);
        }

        $listOfValue = $listOfValue->where('utility_list_of_values.code', $code)
            ->select('utility_list_of_values.*')->first();

        if ($object) {
            return $listOfValue ?: optional();
        } else {
            return optional($listOfValue)->{$attribute} ?? $default;
        }
    }


    public function insertListOfValuesChildren($parent, $options)
    {
        foreach ($options as $code => $attributes) {

            if (is_array($attributes)) {
                $label = data_get($attributes, 'label');
                $value = data_get($attributes, 'value', $code);
                $properties = data_get($attributes, 'properties');
            } else {
                $value = $code;
                $label = $attributes;
            }

            ListOfValue::query()->create([
                'code' => $code,
                'value' => $value,
                'label' => $label,
                'parent_id' => $parent->id,
                'properties' => $properties ?? null,
                'module' => $parent->module,
            ]);
        }
    }
}
