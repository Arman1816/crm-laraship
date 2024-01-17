<?php

namespace Corals\Modules\Utility\Transformers\Guide;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Utility\Models\Guide\Guide;

class GuideTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('utility.models.guide.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Guide $guide
     * @return array
     * @throws \Throwable
     */
    public function transform(Guide $guide)
    {
        $transformedArray = [
            'id' => $guide->id,
            'url' => $guide->url ?? '-',
            'route' => $guide->route ?? '-',
            'status' => formatStatusAsLabels($guide->status),
            'created_at' => format_date($guide->created_at),
            'updated_at' => format_date($guide->updated_at),
            'guide_config' => generatePopover(formatProperties($guide->getProperty('guide_config'))),
            'action' => $this->actions($guide),
        ];

        return parent::transformResponse($transformedArray);
    }
}
