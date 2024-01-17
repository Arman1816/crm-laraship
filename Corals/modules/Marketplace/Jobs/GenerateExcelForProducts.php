<?php

namespace Corals\Modules\Marketplace\Jobs;

use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\AttributeOption;
use Corals\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Yajra\DataTables\EloquentDataTable;

class GenerateExcelForProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataTable;
    protected $scopes;
    protected $columns;
    protected $user;
    protected $tableID;
    protected $download;


    /**
     * GenerateCSVForDataTable constructor.
     * @param $dataTable
     * @param $scopes
     * @param $columns
     * @param $tableID
     * @param User $user
     * @param bool $download
     */
    public function __construct($dataTable, $scopes, $columns, $tableID, User $user, $download = false)
    {
        $this->dataTable = $dataTable;
        $this->scopes = $scopes;
        $this->columns = $columns;
        $this->user = $user;
        $this->tableID = $tableID;
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function handle()
    {
        try {
            logger('start exporting: ' . $this->dataTable);

            $dataTable = app()->make($this->dataTable);

            $query = app()->call([$dataTable, 'query']);

            $dt = new EloquentDataTable($query);

            $source = $dt->getFilteredQuery();

            //apply scopes
            foreach ($this->scopes as $scope) {
                $scope->apply($source);
            }

            $rootPath = config('app.export_excel_base_path');

            $exportName = join('_', [
                'marketplace_products_export',
                'user_id_' . $this->user->id,
                str_replace(['-', ':', ' '], '_', now()->toDateTimeString()) . '.csv'
            ]);

            $filePath = storage_path($rootPath . $exportName);

            if (!file_exists($rootPath = storage_path($rootPath))) {
                mkdir($rootPath, 0755, true);
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $writer = Writer::createFromPath($filePath, 'w+')
                ->setDelimiter(config('corals.csv_delimiter', ','));

            $headers = trans("Marketplace::import.product-headers");

            $writer->insertOne(array_keys($headers));

            $source->chunk(100, function ($data) use ($writer) {
                foreach ($data as $product) {
                    try {
                        $attributeSets = trim(join('|', $product->attributeSets->pluck('code')->toArray()), '| ');

                        $productAttributesString = '';

                        if ($product->type !== 'simple') {
                            $attributes = $product->variation_options;

                            $productAttributes = Attribute::query()->whereIn('id',
                                $product->options()->pluck('attribute_id')->toArray())->get();

                            foreach ($productAttributes as $attribute) {
                                $options = $sku->options()->where('attribute_id', $attribute->id)->get();

                                $value = null;

                                switch ($attribute->type) {
                                    case 'select':
                                    case 'multi_values':
                                        // in case of multiple type
                                        $value = AttributeOption::whereIn('id',
                                            $options->pluck('number_value')->toArray())
                                            ->pluck('option_value')->toArray();
                                        $value = join('+', $value);
                                        break;
                                    default:
                                        $option = $options->first();
                                        $value = optional($option)->value;
                                }

                                if ($value) {
                                    $productAttributesString .= $attribute->code . ':' . $value . '|';
                                }
                            }

                            $productAttributesString = trim($productAttributesString, '|');
                        } else {
                            $attributesId = $product->sku->first()->options()->pluck('attribute_id')->toArray();

                            $attributes = Attribute::query()->whereIn('id', $attributesId)->get();
                        }

                        $categories = trim(join('|', $product->categories->pluck('name')->toArray()), '| ');

                        $galleryImages = [];

                        $gallery = $product->getMedia($product->galleryMediaCollection);

                        $featuredImage = null;

                        foreach ($gallery as $item) {
                            if ($item->hasCustomProperty('featured')) {
                                $featuredImage = $item->file_name;
                            }

                            $galleryImages[] = $item->file_name;
                        }

                        $galleryImages = join('|', $galleryImages);

                        foreach ($product->sku as $sku) {
                            $attributesString = '';

                            foreach ($attributes as $attribute) {
                                $options = $sku->options()->where('attribute_id', $attribute->id)->get();

                                $value = null;

                                switch ($attribute->type) {
                                    case 'select':
                                    case 'multi_values':
                                        // in case of multiple type
                                        $value = AttributeOption::whereIn('id',
                                            $options->pluck('number_value')->toArray())
                                            ->pluck('option_value')->toArray();
                                        $value = join('+', $value);
                                        break;
                                    default:
                                        $option = $options->first();
                                        $value = optional($option)->value;
                                }
                                if ($value) {
                                    $attributesString .= $attribute->code . ':' . $value . '|';
                                }
                            }

                            $attributesString = trim($attributesString, '|');

                            if ($product->type === 'simple') {
                                $productAttributesString = $attributesString;
                                $attributesString = '';
                            }

                            $skuImage = '';

                            $media = $sku->getFirstMedia($sku->mediaCollectionName);

                            if ($media) {
                                $skuImage = $media->file_name;
                            }

                            $productExportData = [
                                'SKU' => $sku->code,
                                'Parent SKU' => $product->product_code,
                                'Type' => $product->type,
                                'Name' => $product->name,
                                'Short Description' => $product->caption,
                                'Description' => $product->description,
                                'Status' => $sku->status,
                                'Attribute Sets' => $attributeSets,
                                'Product Attributes' => $productAttributesString,
                                'Attributes' => $attributesString,
                                'Brand Name' => optional($product->brand)->name,
                                'Categories' => $categories,
                                'Featured Image' => $skuImage ?: $featuredImage,
                                'Images' => $galleryImages,
                                'Regular Price' => $sku->getRawOriginal('regular_price'),
                                'Sale Price' => $sku->getRawOriginal('sale_price'),
                                'Inventory' => $sku->inventory,
                                'Inventory Value' => $sku->inventory_value,
                                'Shippable' => $sku->shipping || $product->shipping ? 1 : 0,
                                'Width' => $sku->shipping['width'] ?? data_get($product, 'shipping.width'),
                                'Height' => $sku->shipping['height'] ?? data_get($product, 'shipping.height'),
                                'Length' => $sku->shipping['length'] ?? data_get($product, 'shipping.length'),
                                'Weight' => $sku->shipping['weight'] ?? data_get($product, 'shipping.weight'),
                            ];

                            $writer->insertOne($productExportData);
                        }
                    } catch (CannotInsertRecord $exception) {
                        logger(self::class);
                        logger($exception->getMessage());
                        logger($exception->getRecord());
                    } catch (\Exception $exception) {
                    }
                }
            });

            if ($this->download) {
                logger($exportName . ' Completed');
                return response()->download($filePath);
            }

            event('notifications.user.send_excel_file', [
                'file' => $filePath,
                'user' => $this->user,
                'table_id' => $this->tableID
            ]);

            logger($exportName . ' Completed');
        } catch (\Exception $exception) {
            report($exception);
        }
    }
}

