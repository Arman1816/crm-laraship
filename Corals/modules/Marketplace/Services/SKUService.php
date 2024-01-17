<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Traits\DownloadableController;
use Faker\Provider\Uuid;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SKUService extends BaseServiceClass
{
    use DownloadableController;

    protected $excludedRequestParams = [
        'options',
        'image',
        'clear',
        'downloads_enabled',
        'downloads',
        'cleared_downloads'
    ];

    protected function postStoreUpdate($request, $additionalData)
    {
        $sku = $this->model;

        if ($request->has('clear') || $request->hasFile('image')) {
            $sku->clearMediaCollection('marketplace-sku-image');
        }

        if ($request->hasFile('image') && !$request->has('clear')) {
            $sku->addMedia($request->file('image'))->withCustomProperties(['root' => 'user_' . user()->hashed_id])->toMediaCollection('marketplace-sku-image');
        }

        $this->createOptions($request->get('options', []), $sku);

        $this->handleDownloads($request, $sku);
    }

    public function createOptions($optionsList, $sku)
    {
        $sku->options()->delete();

        $options = [];

        if (isset($optionsList)) {
            foreach ($optionsList as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_option) {
                        $options[] = [
                            'attribute_id' => $key,
                            'value' => $value_option
                        ];
                    }
                } else {
                    $options[] = [
                        'attribute_id' => $key,
                        'value' => $value
                    ];
                }
            }

            $sku->options()->createMany($options);
        }
    }

    public function createSKUFromBulk($data)
    {
        $skuData = $this->getRequestData($data);

        $sku = SKU::query()->create($skuData);

        $this->createOptions(data_get($data, 'options', []), $sku);

        return $sku;
    }

    /**
     * @param $request
     * @param $model
     * @throws \Exception
     */
    public function destroy($request, $model)
    {
        $sku = $model;

        $gateways = \Payments::getAvailableGateways();

        foreach ($gateways as $gateway => $gateway_title) {
            $Marketplace = new Marketplace($gateway);
            if (!$Marketplace->gateway->getConfig('manage_remote_sku')) {
                continue;
            }
            $Marketplace->deleteSKU($sku);

            $sku->setGatewayStatus($this->gateway->getName(), 'DELETED', null);
        }

        $sku->clearMediaCollection('marketplace-sku-image');

        $sku->delete();
    }

    /**
     * @param $data
     * @param $product
     * @param array $extraData
     * @param false $skipDuplicatedSKU
     * @return string
     */
    public function generateSKUs($data, $product, $extraData = [], $skipDuplicatedSKU = false)
    {
        $attributes = Attribute::query()
            ->whereIn('id', array_keys(data_get($data, 'options')))
            ->orderBy('required', 'desc')
            ->get();

        $result = $this->get_all_options_combinations(data_get($data, 'options'));

        $hasSKUs = !!$product->sku()->count();

        $bulkCode = Uuid::uuid();

        $skuService = new SKUService();

        foreach ($result as $row) {
            $options = [];
            $skuCode = [$product->product_code];

            foreach ($row as $option) {
                [$optionId, $value] = explode(':', $option);

                $attribute = $attributes->where('id', $optionId)->first();

                switch ($attribute->type) {
                    case 'checkboxes':
                    case 'multi_values':
                        $options[$optionId] = [$value];
                        break;
                    default:
                        $options[$optionId] = $value;
                }

                switch ($attribute->type) {
                    case 'checkboxes':
                    case 'multi_values':
                    case 'select':
                    case 'radio':
                        $attributeOption = $attribute->options()->where('marketplace_attribute_options.id',
                            $value)->first();

                        $skuCode[] = $attributeOption->option_value;
                        break;
                    default:
                        $skuCode[] = $value;
                }
            }

            $code = strtolower(join('-', $skuCode));

            $skuExists = $product->sku()->where('code', $code)->exists();

            if ($skuExists && $skipDuplicatedSKU) {
                continue;
            }

            if ($hasSKUs && $skuExists) {
                $skuCode[] = Str::random(3);
                $code = strtolower(join('-', $skuCode));
            }

            $data = [
                'code' => $code,
                'regular_price' => 0,
                'status' => 'inactive',
                'product_id' => $product->id,
                'shipping' => $product->shipping,
                'inventory' => 'bucket',
                'inventory_value' => 'in_stock',
                'properties' => [
                    'bulk_code' => $bulkCode
                ],
                'options' => $options
            ];


            $sku = $skuService->createSKUFromBulk(array_merge($data, $extraData));
        }

        return $bulkCode;
    }

    /**
     * @param $arrays
     * @return array|array[]
     */
    protected function get_all_options_combinations($arrays)
    {
        $result = [[]];

        foreach ($arrays as $property => $property_values) {
            $tmp = [];

            foreach ($result as $result_item) {
                foreach (Arr::wrap($property_values) as $property_value) {
                    $tmp[] = array_merge($result_item, [$property . ':' . $property_value]);
                }
            }

            $result = $tmp;
        }

        return $result;
    }

}
