<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\SKU;

class SKUTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_route = config('marketplace.models.sku.resource_route');

        parent::__construct($extras);
    }

    /**
     * @param SKU $sku
     * @return array
     * @throws \Throwable
     */
    public function transform(SKU $sku)
    {

        $sku_options = [];
        $options = $sku->options ?? [];
        foreach ($options as $option) {
            $sku_options[$option->attribute->label] = $option->formatted_value;
        }

        $transformedArray = [
            'id' => $sku->id,
            'code' => $sku->code ?? '-',
            'image' => '<img src="' . $sku->image . '" class=" img-responsive" alt="SKU Image" style="max-height: 50px;width: auto;"/></a>',
            'checkbox' => $this->generateCheckboxElement($sku),
            'price' => $sku->price,
            'inventory' => trim("{$sku->inventory}: {$sku->inventory_value}", ': '),
            'gateway_status' => $sku->getGatewayStatus(),
            'inventory_value' => $sku->inventory_value,
            'options' => \Filters::do_filter('marketplace_sku_options', formatArrayAsLabels($sku_options, 'info', null, true), $sku),
            'sku_options' => implode('|', $sku_options),
            'dt_options' => generatePopover(formatArrayAsLabels($sku_options, 'info', null, true)),
            'status' => formatStatusAsLabels($sku->status),

            'created_at' => format_date($sku->created_at),
            'updated_at' => format_date($sku->updated_at),
            'action' => $this->actions($sku, $sku->getGatewayActions($sku))
        ];

        return parent::transformResponse($transformedArray);
    }
}
