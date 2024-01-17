<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Brand;

class BrandTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.brand.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Brand $brand
     * @return array
     * @throws \Throwable
     */
    public function transform(Brand $brand)
    {

        $logo = '<img src = "' . $brand->thumbnail . '" 
        class="img-responsive img-fluid img-rounded" 
        style ="max-height: 20px;width:auto" alt = "Thumbnail" />';

        $transformedArray = [
            'id' => $brand->id,
            'checkbox' => $this->generateCheckboxElement($brand),
            'name' => \Str::limit($brand->name, 50),
            'slug' => $brand->slug,
            'logo' => $logo,
            'store' => $brand->store ? $brand->store->name : '-',
            'products_count' => $brand->products_count,
            'status' => formatStatusAsLabels($brand->status),
            'is_featured' => $brand->is_featured ? '<i class="fa fa-check text-success"></i>' : '-',
            'created_at' => format_date($brand->created_at),
            'updated_at' => format_date($brand->updated_at),
            'action' => $this->actions($brand)
        ];

        return parent::transformResponse($transformedArray);
    }
}
