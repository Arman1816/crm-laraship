<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;

class PackageTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.package.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Package $package
     * @return array
     * @throws \Throwable
     */
    public function transform(Package $package)
    {
        $transformedArray = [
            'id' => $package->id,
            'name' => $package->name,
            'store' => $package->store ? $package->store->name : '-',
            'dimension_template' => ListOfValues::getLOVByCode($package->dimension_template, null, true)->label ?? '-',
            'dimensions' => sprintf("%s x %s x %s %s",
                number_format($package->length, 1),
                number_format($package->width, 1),
                number_format($package->height, 1),
                $package->distance_unit),
            'length' => number_format($package->length, 1),
            'width' => number_format($package->width, 1),
            'height' => number_format($package->height, 1),
            'distance_unit' => $package->distance_unit,
            'package_weight' => $package->weight ? sprintf("%s %s", number_format($package->weight, 1),
                $package->mass_unit) : '-',
            'weight' => number_format($package->weight, 1),
            'mass_unit' => $package->mass_unit,
            'integration_id' => $package->integration_id,
            'action' => $this->actions($package)
        ];

        return parent::transformResponse($transformedArray);
    }
}
