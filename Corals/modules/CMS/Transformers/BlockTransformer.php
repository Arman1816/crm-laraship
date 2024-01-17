<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\CMS\Models\Block;

class BlockTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('cms.models.block.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Block $block
     * @return array
     * @throws \Throwable
     */
    public function transform(Block $block)
    {
        $transformedArray = [
            'id' => $block->id,
            'name' => \Str::limit($block->name, 50),
            'key' => $block->key,
            'status' => formatStatusAsLabels($block->status),
            'created_at' => format_date($block->created_at),
            'updated_at' => format_date($block->updated_at),
            'short_code' => $this->getShortcode($block),
            'action' => $this->actions($block)
        ];

        return parent::transformResponse($transformedArray);
    }

    protected function getShortcode($block)
    {
        return '<b id="shortcode_' . $block->id . '">@block(' . $block->key . ')</b> 
                <a href="#" onclick="event.preventDefault();" class="copy-button"
                data-clipboard-target="#shortcode_' . $block->id . '"><i class="fa fa-clipboard"></i></a>';
    }
}
