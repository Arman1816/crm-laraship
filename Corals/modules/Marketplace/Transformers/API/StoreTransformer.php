<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Store;

class StoreTransformer extends APIBaseTransformer
{
    /**
     * @param Store $store
     * @return array
     * @throws \Throwable
     */
    public function transform(Store $store)
    {
        $transformedArray = [
            'id' => $store->id,
            'name' => $store->name,
            'short_description' => $store->short_description,
            'cover_photo' => $store->cover_photo,
            'parking_domain' => $store->parking_domain,
            'thumbnail' => $store->thumbnail,
            'user_id' => $store->user_id,
            'user' => $store->user->full_name,
            'slug' => $store->slug,
            'created_at' => format_date($store->created_at),
            'status' => $store->status,
            'is_featured' => $store->is_featured,
            'updated_at' => format_date($store->updated_at),
        ];

        $settings = config('marketplace.store_settings');

        foreach ($settings as $setting_key => $setting_items) {
            $settingItemsList = [];

            foreach ($setting_items ?? [] as $key => $setting) {
                $setting_concat = 'marketplace_' . strtolower($setting_key) . '_' . $key;
                $setting_field = $setting_concat . '|' . $setting['cast_type'];

                $settingItemsList[$setting_field]['value'] = $store->getSettingValue($setting_concat);
                $settingItemsList[$setting_field]['label'] = trans($setting['label']);
                $settingItemsList[$setting_field]['type'] = $setting['type'];
                $settingItemsList[$setting_field]['required'] = $setting['required'] ?? false;

                if (!empty($setting['options'])) {
                    $settingItemsList[$setting_field]['options'] = is_array($setting['options']) ? $setting['options'] : eval($setting['options']);
                }
            }

            $transformedArray[$setting_key] = $settingItemsList;
        }

        return parent::transformResponse($transformedArray);
    }
}
