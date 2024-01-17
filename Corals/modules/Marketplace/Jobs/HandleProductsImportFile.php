<?php


namespace Corals\Modules\Marketplace\Jobs;


use Corals\Modules\Marketplace\Facades\Marketplace;
use Corals\Modules\Marketplace\Http\Requests\{ProductRequest, SKURequest};
use Corals\Modules\Marketplace\Models\{Attribute, AttributeSet, Brand, Category, Product, SKU};
use Corals\Modules\Marketplace\Services\{ProductService, SKUService};
use Corals\Modules\Marketplace\Traits\ImportTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\{Arr, Str};
use League\Csv\{Exception as CSVException};

class HandleProductsImportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImportTrait;

    protected $importFilePath;
    protected $storeId;

    /**
     * @var Collection
     */
    protected $categories;

    /**
     * @var Collection
     */
    protected $brands;
    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * @var Collection
     */
    protected $attributeSets;

    /**
     * @var array
     */
    protected $importHeaders;
    protected $user;
    protected $images_root;
    protected $clearExistingImages;

    /**
     * HandleProductsImportFile constructor.
     * @param $importFilePath
     * @param $images_root
     * @param $clearExistingImages
     * @param $storeId
     * @param $user
     */
    public function __construct($importFilePath, $images_root, $clearExistingImages, $storeId, $user)
    {
        $this->user = $user;
        $this->importFilePath = $importFilePath;
        $this->clearExistingImages = $clearExistingImages;
        $this->images_root = $images_root;
        $this->importHeaders = array_keys(trans('Marketplace::import.product-headers'));

        $this->storeId = $storeId;
    }


    /**
     * @throws CSVException
     */
    public function handle()
    {
        $this->doImport();
    }

    /**
     * @param $record
     * @throws \Exception
     */
    protected function handleImportRecord($record)
    {
        $record = array_map('trim', $record);

        //prepare product data
        $productData = $productRequestData = $this->getProductData($record);

        $productRequestData['variation_options'] = array_keys(data_get($productRequestData, 'variation_options', []));

        unset($productRequestData['shippable']);

        //validate record
        $this->validateRecord($productRequestData);

        //check if product/sku exist
        $skuCode = data_get($record, 'SKU');

        $skuModel = SKU::query()
            ->where('code', $skuCode)
            ->first();

        $productCode = data_get($record, 'Parent SKU');

        if ($productCode) {
            $productModel = Product::query()
                ->where('product_code', $productCode)
                ->first();
        } elseif ($skuModel) {
            $productModel = $skuModel->product;
        }

        $productRequest = new ProductRequest();

        $productRequest->replace($productRequestData);

        $productService = new ProductService();

        if (isset($productModel) && $productModel) {
            $productModel = $productService->update($productRequest, $productModel);
        } else {
            $productModel = $productService->store($productRequest, Product::class);
        }

        $this->handleProductImages($record, $productModel);

        if ($productData['type'] === 'variable') {
            $productData['product_id'] = $productModel->id;
            $skuData = $this->getSKUData($productData);
            $skuService = new SKUService();
            $skuRequest = new SKURequest();
            $skuRequest->replace($skuData);

            if ($skuModel) {
                $skuModel = $skuService->update($skuRequest, $skuModel);
            } else {
                $skuModel = $skuService->store($skuRequest, SKU::class);
            }
            $featuredImage = data_get($record, 'Featured Image');

            if ($featuredImage) {
                $this->addMediaFromFile(
                    $skuModel,
                    $featuredImage,
                    $skuModel->mediaCollectionName,
                    "sku_{$skuModel->id}");
            }
        }

        unset($skuModel);
        unset($productModel);
    }

    /**
     * @param $record
     * @return array
     */
    protected function handleProductCategories($record): array
    {
        $importCategories = array_filter(explode('|', data_get($record, 'Categories', [])));

        $productCategories = [];

        foreach ($importCategories as $categoryName) {
            $categoryName = trim($categoryName);

            if (empty($categoryName)) {
                continue;
            }

            $categoryFound = $this->categories->where('name', $categoryName)->first();

            if (!$categoryFound) {
                $categoryFound = $this->categories->where('slug', Str::slug($categoryName))->first();
            }

            if ($categoryFound) {
                $productCategories[] = $categoryFound->id;
            } else {
                $newCategory = Category::query()->create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                ]);

                $this->categories->push($newCategory);

                $productCategories[] = $newCategory->id;
            }
        }

        return $productCategories;
    }

    /**
     * @param $record
     * @return int|null
     */
    protected function handleProductBrand($record)
    {
        $brandName = data_get($record, 'Brand Name');

        $brandId = null;

        if ($brandName) {
            $brandFound = $this->brands->where('name', $brandName)->first();

            if (!$brandFound) {
                $brandFound = $this->categories->where('slug', $brandName)->first();
            }

            if (!$brandFound) {
                $newBrand = Brand::query()->create([
                    'name' => $brandName,
                    'slug' => Str::slug($brandName),
                ]);

                $this->brands->push($newBrand);
                $brandFound = $newBrand;
            }

            $brandId = $brandFound->id;
        }

        return $brandId;
    }

    /**
     * @param $record
     * @param $productModel
     */
    protected function handleProductImages($record, $productModel)
    {
        if ($this->clearExistingImages) {
            $productModel->clearMediaCollection($productModel->galleryMediaCollection);
        }

        $featuredImage = data_get($record, 'Featured Image');

        if ($featuredImage) {
            $media = $this->addMediaFromFile(
                $productModel,
                $featuredImage,
                $productModel->galleryMediaCollection,
                "product_{$productModel->id}", false, ['featured' => true]);

            if ($media && !$this->clearExistingImages) {
                $gallery = $productModel->getMedia($productModel->galleryMediaCollection);

                foreach ($gallery as $item) {
                    if ($item->id != $media->id) {
                        $item->forgetCustomProperty('featured');
                        $item->save();
                    }
                }
            }
        }

        $images = array_filter(explode('|', data_get($record, 'Images')));

        foreach ($images as $image) {
            if ($featuredImage == $image) {
                continue;
            }

            $this->addMediaFromFile(
                $productModel,
                $image,
                $productModel->galleryMediaCollection,
                "product_{$productModel->id}", false);
        }
    }

    protected function loadMarketplaceCategories()
    {
        $this->categories = Category::query()->get();
    }

    protected function loadMarketplaceBrands()
    {
        $this->brands = Brand::query()->get();
    }

    protected function loadMarketplaceAttributes()
    {
        $this->attributes = Attribute::with(['options'])->get();
    }

    protected function loadMarketplaceAttributeSets()
    {
        $this->attributeSets = AttributeSet::query()->get();
    }

    /**
     * @param $record
     * @param string $column
     * @return array
     * @throws \Exception
     */
    protected function handleVariationOptions($record, string $column)
    {
        $attributes = array_filter(explode('|', data_get($record, $column)));

        $isProductLevel = $column !== 'Attributes';

        $variationOptions = [];

        foreach ($attributes as $attribute) {
            [$code, $value] = explode(':', $attribute);

            $value = trim($value);

            $attributeModel = $this->attributes->where('code', trim($code))->first();

            if (!$attributeModel) {
                throw new \Exception("Attribute $code not found");
            }

            if (empty($value) && $attributeModel->required) {
                throw new \Exception("Attribute $attribute value is empty");
            }

            if ($attributeModel->options->isNotEmpty()) {
                if ($attributeModel->type == 'multi_values') {
                    $variationOptions[$attributeModel->id] = ['multi' => []];

                    $values = array_filter(explode('+', $value));

                    foreach ($values as $value) {
                        $option = $this->getAttributeOption($attributeModel, $value);

                        $variationOptions[$attributeModel->id]['multi'][] = [$option->id => $value];
                    }
                } else {
                    $option = $this->getAttributeOption($attributeModel, $value);

                    $variationOptions[$attributeModel->id] = [$option->id => $value];
                }
            } else {
                $variationOptions[$attributeModel->id] = $value;
            }
        }
        if ($isProductLevel) {
            $set_attribute_options = [];
            foreach ($variationOptions as $optionId => $option) {
                if (is_array($option)) {
                    $key = key($option);
                    if ($key == 'multi') {
                        foreach ($option[$key] as $multiOption) {
                            $set_attribute_options[$optionId][] = key($multiOption);
                        }
                    } else {
                        $set_attribute_options[$optionId] = $key;
                    }
                } else {
                    $set_attribute_options[$optionId] = $option;
                }
            }

            $variationOptions = $set_attribute_options;
        }

        return $variationOptions;
    }

    /**.
     * @param $attributeModel
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    protected function getAttributeOption($attributeModel, $value)
    {
        $option = $attributeModel->options->where('option_value', $value)->first();

        if (!$option) {
            throw new \Exception("Attribute {$attributeModel->code} $value option not found");
        }

        return $option;
    }

    /**
     * @param $record
     * @return array
     */
    protected function getShippingDetails($record): array
    {
        return array_filter([
            'shipping_option' => 'calculate_rates',
            'enabled' => data_get($record, 'Shippable') == 1 ? 1 : 0,
            'width' => data_get($record, 'Width'),
            'height' => data_get($record, 'Height'),
            'length' => data_get($record, 'Length'),
            'weight' => data_get($record, 'Weight'),
        ]);
    }

    /**
     * @param $record
     * @return array
     * @throws \Exception
     */
    protected function getProductData($record)
    {
        $productCategories = $this->handleProductCategories($record);

        $brandId = $this->handleProductBrand($record);

        $variationOptions = $this->handleVariationOptions($record, 'Attributes');

        $productAttributes = $this->handleVariationOptions($record, 'Product Attributes');

        $productAttributeSets = $this->handleAttributeSets($record);

        return array_filter([
            'name' => data_get($record, 'Name'),
            'caption' => data_get($record, 'Short Description'),
            'product_code' => data_get($record, 'Parent SKU') ?: null,
            'type' => data_get($record, 'Type'),
            'status' => data_get($record, 'Status'),
            'code' => data_get($record, 'SKU'),
            'regular_price' => data_get($record, 'Regular Price'),
            'sale_price' => data_get($record, 'Sale Price'),
            'allowed_quantity' => '0',
            'categories' => $productCategories,
            'description' => data_get($record, 'Description'),
            'attribute_sets' => $productAttributeSets,
            'set_attribute_options' => $productAttributes,
            'variation_options' => $variationOptions,
            'shipping' => $this->getShippingDetails($record),
            'shippable' => data_get($record, 'Shippable'),
            'inventory' => data_get($record, 'Inventory'),
            'inventory_value' => data_get($record, 'Inventory Value'),
            'brand_id' => $brandId,
            'store_id' => $this->storeId,
        ]);
    }

    /**
     * @param array $productData
     * @return array
     */
    protected function getSKUData(array $productData)
    {
        $skuData = Arr::only($productData, [
            'regular_price',
            'sale_price',
            'allowed_quantity',
            'code',
            'status',
            'inventory',
            'inventory_value',
            'shipping',
            'product_id',
        ]);

        foreach ($productData['variation_options'] as $optionId => $option) {
            if (is_array($option)) {
                $key = key($option);
                if ($key == 'multi') {
                    foreach ($option[$key] as $multiOption) {
                        $skuData['options'][$optionId][] = key($multiOption);
                    }
                } else {
                    $skuData['options'][$optionId] = $key;
                }
            } else {
                $skuData['options'][$optionId] = $option;
            }
        }

        return $skuData;
    }

    protected function initHandler()
    {
        $this->loadMarketplaceCategories();
        $this->loadMarketplaceBrands();
        $this->loadMarketplaceAttributeSets();
        $this->loadMarketplaceAttributes();
    }

    protected function getValidationRules($data, $model): array
    {
        return [
            'name' => 'required|max:191',
            'caption' => 'required',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:simple,variable',
            'inventory' => 'required_if:type,simple',
            'inventory_value' => 'required_if:inventory,finite,bucket',
            'regular_price' => 'required_if:type,simple',
            'code' => 'required_if:type,simple',
            'product_code' => 'required_if:type,variable',
            'shipping.width' => 'required_if:shippable,1',
            'shipping.height' => 'required_if:shippable,1',
            'shipping.length' => 'required_if:shippable,1',
            'shipping.weight' => 'required_if:shippable,1',
            'variation_options' => [
                'required_if:type,variable',
                function ($attribute, $value, $fail) use ($data) {
                    $set_attribute_options = data_get($data, 'set_attribute_options', []);
                    if (array_intersect($value, array_keys($set_attribute_options))) {
                        $fail($attribute . ' should be unique with product attributes');
                    }
                }
            ],
            'categories' => 'required',
        ];
    }

    protected function handleAttributeSets($record)
    {
        $importSets = array_filter(explode('|', data_get($record, 'Attribute Sets', [])));

        $productSets = [];

        foreach ($importSets as $setCode) {
            $setCode = trim($setCode);

            if (empty($setCode)) {
                continue;
            }

            $setFound = $this->attributeSets->where('code', $setCode)->first();

            if (!$setFound) {
                throw new \Exception("Attribute Set $setCode not found");
            }


            $productSets[] = $setFound->id;
        }

        if (empty($productSets)) {
            $productSets = Marketplace::getDefaultAttributeSets()->pluck('id')->toArray();
        }

        return $productSets;
    }
}
