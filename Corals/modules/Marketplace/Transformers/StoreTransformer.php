<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Store;
use Illuminate\Support\Str;

class StoreTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.store.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Store $store
     * @return array
     * @throws \Throwable
     */
    public function transform(Store $store)
    {
        $storeUser = $store->user;

        $gatewayStatus = $storeUser->getGatewayStatus(null, 'AccountConnect');

        if (Str::contains($gatewayStatus, '<li>NA</li>')) {
            $gatewayStatus = '-';
        }

        $transformedArray = [
            'id' => $store->id,
            'checkbox' => $this->generateCheckboxElement($store),
            'parking_domain' => $store->parking_domain,
            'name' => $store->name,
            'code' => $store->code ?? '-',
            'user_id' => "<a href='" . url('users/' . $storeUser->hashed_id) . "'> {$storeUser->full_name}</a>",
            'slug' => '<a target="_blank" href="' . $store->getUrl() . '">' . $store->slug . '</a>',
            'gatewayStatus' => $gatewayStatus,
            'created_at' => format_date($store->created_at),
            'status' => formatStatusAsLabels($store->status,
                ['text' => trans('Marketplace::status.store.' . $store->status)]),
            'is_featured' => $store->is_featured ? '<i class="fa fa-check text-success"></i>' : '-',

            'updated_at' => format_date($store->updated_at),
            'action' => $this->actions($store)
        ];

        return parent::transformResponse($transformedArray);
    }
}
