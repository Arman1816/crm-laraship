<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Foundation\DataTables\CoralsScope;
use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\CMS\DataTables\CategoriesDataTable;
use Corals\Modules\CMS\Http\Requests\CategoryRequest;
use Corals\Modules\CMS\Models\Category;
use Corals\Modules\CMS\Services\CategoryService;
use Corals\Modules\CMS\Transformers\API\CategoryPresenter;

class CategoriesController extends APIBaseController
{
    protected $categoryService;

    /**
     * CategoriesController constructor.
     * @param CategoryService $categoryService
     * @throws \Exception
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->categoryService->setPresenter(new CategoryPresenter());

        parent::__construct();
    }

    /**
     * @param CategoryRequest $request
     * @param CategoriesDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(CategoryRequest $request, CategoriesDataTable $dataTable)
    {
        $categories = $dataTable->query(new Category());

        return $this->categoryService->index($categories, $dataTable);
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->store($request, Category::class);

            return apiResponse($this->categoryService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $category->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category = $this->categoryService->update($request, $category);

            return apiResponse($this->categoryService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $category->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CategoryRequest $request, Category $category)
    {
        try {
            $name = $category->name;

            $this->categoryService->destroy($category);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
