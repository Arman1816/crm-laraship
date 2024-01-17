<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class SKUOption extends BaseModel
{
    use PresentableTrait, LogsActivity;

    protected $table = 'marketplace_sku_options';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.sku_option';

    protected static $logAttributes = [];


    protected $guarded = ['id'];

    public function sku()
    {
        return $this->belongsTo(SKU::class, 'sku_id');
    }

    public function Attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * Get value for current option field
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        $attributeName = $this->getAttributeName();

        $value = $this->$attributeName;

        return $value;
    }

    public function getFormattedValueAttribute()
    {
        $attributes = optional($this->attribute);
        $type = $attributes->type;
        $displayType = $attributes->getProperty('display_type');


        $value = '';

        switch ($type) {
            case 'checkbox':
                $value = $this->value ? '&#10004;' : '-';
                break;
            case 'text':
            case 'date':
            case 'textarea':
            case 'number':
                $value = $this->value;
                break;
            case 'multi_values':
                $skuOptions = $this->sku->options->where('attribute_id', $this->attribute->id)->pluck('number_value')->toArray();

                $options = AttributeOption::whereIn('id', $skuOptions)->get();

                foreach ($options as $option) {
                    $value .= $option->option_display . ', ';
                }
                $value = trim($value, ', ');
                break;
            case 'select':
            case 'radio':
                $value = $this->value;

                $option = $this->attribute->options()->where('id', $value)->first();

                if ($option) {
                    $value = $option->option_display;
                }

                if ($displayType == 'color') {
                    $value = "<div title='{$option->option_value}' style=\"display:inline-block;background-color:{$value};height: 100%;width: 25px;\">&nbsp;</div>";
                } elseif ($displayType == 'image') {
                    $value = sprintf("<img title='{$option->option_value}' src='%s' style='max-width: 20px;max-height: 20px' alt='img'>",
                        $option->media()->first()->getFullUrl());
                }

                break;
            case 'color':
                $value = "<div style=\"display:inline-block;background-color:{$this->value};height: 100%;width: 25px;\">&nbsp;</div>";
                break;
            default:
                $value = $this->value;
                break;
        }


        return $value;
    }

    /**
     * Return column name for current custom field value
     *
     * @return string
     */
    public function getAttributeName()
    {
        $type = optional($this->attribute)->type;

        switch ($type) {
            case 'checkbox':
            case 'text':
            case 'date':
                $name = 'string_value';
                break;
            case 'textarea':
                $name = 'text_value';
                break;
            case 'number':
            case 'select':
            case 'multi_values':
            case 'radio':
                $name = 'number_value';
                break;
            default:
                $name = 'string_value';
        }

        return $name;
    }

    /**
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setValueAttribute($value)
    {
        if ($value instanceof self) {
            throw new \Exception(trans('Marketplace::exception.sku.invalid_custom'));
        }

        $attributeName = $this->getAttributeName();

        $this->$attributeName = $value;

        return $this;
    }
}
