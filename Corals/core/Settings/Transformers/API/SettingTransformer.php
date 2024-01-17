<?php

namespace Corals\Settings\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Settings\Models\Setting;

class SettingTransformer extends APIBaseTransformer
{
    /**
     * @param Setting $setting
     * @return array
     */
    public function transform(Setting $setting)
    {
        $transformedArray = [
            'id' => $setting->id,
            'code' => $setting->code,
            'type' => $setting->type,
            'label' => $setting->label,
            'value' => $setting->getRawOriginal('value'),
            'created_at' => format_date($setting->created_at),
            'updated_at' => format_date($setting->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}