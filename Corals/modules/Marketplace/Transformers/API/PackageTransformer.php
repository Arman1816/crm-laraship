<?php

namespace Corals\Modules\Marketplace\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Marketplace\Models\Package;

class PackageTransformer extends APIBaseTransformer
{
    /**
     * @param Package $package
     * @return array
     */
    public function transform(Package $package)
    {
        $transformedArray = [
            'id' => $package->id,
            'name' => $package->name,
            'store_id' => $package->store_id,
            'store' => optional($package->store)->name,
            'description' => $package->description,
        ];

        return parent::transformResponse($transformedArray);
    }
}
