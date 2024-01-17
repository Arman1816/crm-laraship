<?php


namespace Corals\Modules\Marketplace\Jobs;


use Corals\Modules\Marketplace\Http\Requests\{BrandRequest};
use Corals\Modules\Marketplace\Models\{Brand};
use Corals\Modules\Marketplace\Services\{BrandService};
use Corals\Modules\Marketplace\Traits\ImportTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Str;
use League\Csv\{Exception as CSVException};

class HandleBrandsImportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImportTrait;

    protected $importFilePath;

    /**
     * @var Collection
     */
    protected $brands;

    /**
     * @var array
     */
    protected $importHeaders;
    protected $user;
    protected $images_root;

    /**
     * HandleBrandsImportFile constructor.
     * @param $importFilePath
     * @param $images_root
     * @param $user
     */
    public function __construct($importFilePath, $images_root, $user)
    {
        $this->user = $user;
        $this->importFilePath = $importFilePath;
        $this->images_root = $images_root;
        $this->importHeaders = array_keys(trans('Marketplace::import.brand-headers'));
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

        //prepare brand data
        $brandData = $this->getBrandData($record);

        $brandModel = $this->getBrandModel($brandData['name']);

        //validate record
        $this->validateRecord($brandData, $brandModel);

        $brandRequest = new BrandRequest();

        $brandRequest->replace($brandData);

        $brandService = new BrandService();

        if ($brandModel) {
            $brandModel = $brandService->update($brandRequest, $brandModel);
        } else {
            $brandModel = $brandService->store($brandRequest, Brand::class);
            $this->brands->push($brandModel);
        }

        $brandImage = data_get($record, 'Image');

        if ($brandImage) {
            $this->addMediaFromFile(
                $brandModel,
                $brandImage,
                $brandModel->mediaCollectionName,
                "brand_{$brandModel ->id}");
        }
    }

    protected function getBrandModel($name)
    {
        $brandFound = $this->brands->where('name', $name)->first();

        if (!$brandFound) {
            $brandFound = $this->brands->where('slug', $name)->first();
        }

        return $brandFound;
    }


    protected function loadMarketplaceBrands()
    {
        $this->brands = Brand::query()->get();
    }

    /**
     * @param $record
     * @return array
     * @throws \Exception
     */
    protected function getBrandData($record)
    {
        $slug = data_get($record, 'Slug');

        if (!$slug) {
            $slug = Str::slug(data_get($record, 'Name'));
        }

        $featured = data_get($record, 'Featured') == 1;

        return array_filter([
            'name' => data_get($record, 'Name'),
            'status' => data_get($record, 'Status'),
            'slug' => $slug,
            'is_featured' => $featured,
        ]);
    }

    protected function initHandler()
    {
        $this->loadMarketplaceBrands();
    }

    protected function getValidationRules($data, $model): array
    {
        return [
            'status' => 'required|in:active,inactive',
            'name' => 'required|max:191|unique:marketplace_brands,name' . ($model ? (',' . $model->id) : ''),
            'slug' => 'required|max:191|unique:marketplace_brands,slug' . ($model ? (',' . $model->id) : ''),
        ];
    }
}
