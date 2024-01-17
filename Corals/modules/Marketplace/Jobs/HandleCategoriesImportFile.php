<?php


namespace Corals\Modules\Marketplace\Jobs;


use Corals\Modules\Marketplace\Http\Requests\{CategoryRequest};
use Corals\Modules\Marketplace\Models\{Category};
use Corals\Modules\Marketplace\Services\{CategoryService};
use Corals\Modules\Marketplace\Traits\ImportTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Str;
use League\Csv\{Exception as CSVException};

class HandleCategoriesImportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImportTrait;

    protected $importFilePath;

    /**
     * @var Collection
     */
    protected $categories;

    /**
     * @var array
     */
    protected $importHeaders;
    protected $user;
    protected $images_root;

    /**
     * HandleCategoriesImportFile constructor.
     * @param $importFilePath
     * @param $images_root
     * @param $user
     */
    public function __construct($importFilePath, $images_root, $user)
    {
        $this->user = $user;
        $this->importFilePath = $importFilePath;
        $this->images_root = $images_root;
        $this->importHeaders = array_keys(trans('Marketplace::import.category-headers'));
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

        //prepare category data
        $categoryData = $this->getCategoryData($record);

        $categoryModel = $this->getCategoryModel($categoryData['name']);

        //validate record
        $this->validateRecord($categoryData, $categoryModel);

        $categoryRequest = new CategoryRequest();

        $categoryRequest->replace($categoryData);

        $categoryService = new CategoryService();

        if ($categoryModel) {
            $categoryModel = $categoryService->update($categoryRequest, $categoryModel);
        } else {
            $categoryModel = $categoryService->store($categoryRequest, Category::class);
            $this->categories->push($categoryModel);
        }

        $categoryImage = data_get($record, 'Image');

        if ($categoryImage) {
            $this->addMediaFromFile(
                $categoryModel,
                $categoryImage,
                $categoryModel->mediaCollectionName,
                "category_{$categoryModel ->id}");
        }
    }

    protected function getCategoryModel($name)
    {
        $categoryFound = $this->categories->where('name', $name)->first();

        if (!$categoryFound) {
            $categoryFound = $this->categories->where('slug', $name)->first();
        }

        return $categoryFound;
    }


    protected function loadMarketplaceCategories()
    {
        $this->categories = Category::query()->get();
    }

    /**
     * @param $record
     * @return array
     * @throws \Exception
     */
    protected function getCategoryData($record)
    {
        $parentId = $this->getParentCategory($record);

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
            'parent_id' => $parentId,
        ]);
    }

    protected function initHandler()
    {
        $this->loadMarketplaceCategories();
    }

    protected function getValidationRules($data, $model): array
    {
        return [
            'status' => 'required|in:active,inactive',
            'name' => 'required|max:191|unique:marketplace_categories,name' . ($model ? (',' . $model->id) : ''),
            'slug' => 'required|max:191|unique:marketplace_categories,slug' . ($model ? (',' . $model->id) : ''),
        ];
    }

    /**
     * @param $record
     * @return |null
     * @throws \Exception
     */
    protected function getParentCategory($record)
    {
        $parentId = null;

        $parentName = data_get($record, 'Parent Category');

        if ($parentName) {
            $categoryFound = $this->getCategoryModel($parentName);

            if ($categoryFound) {
                $parentId = $categoryFound->id;
            } else {
                throw new \Exception("Category $parentName not found.");
            }
        }

        return $parentId;
    }
}
