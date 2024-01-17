<?php

namespace Corals\Modules\Utility\Http\Controllers\API\Category;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Utility\DataTables\Category\AttributesDataTable;
use Corals\Modules\Utility\Http\Requests\Category\AttributeRequest;
use Corals\Modules\Utility\Models\Category\Attribute;
use Corals\Modules\Utility\Services\Category\AttributeService;
use Corals\Modules\Utility\Transformers\API\Category\AttributePresenter;

class AttributesController extends APIBaseController
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
        $this->attributeService->setPresenter(new AttributePresenter());

        parent::__construct();
    }

    /**
     * @param AttributeRequest $request
     * @param AttributesDataTable $dataTable
     * @return mixed
     */
    public function index(AttributeRequest $request, AttributesDataTable $dataTable)
    {
        $attributes = $dataTable->query(new Attribute());

        return $this->attributeService->index($attributes, $dataTable);
    }

    /**
     * @param AttributeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttributeRequest $request)
    {
        try {
            $attribute = $this->attributeService->store($request, Attribute::class);

            return apiResponse($this->attributeService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $attribute->label]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param AttributeRequest $request
     * @param Attribute $attribute
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AttributeRequest $request, Attribute $attribute)
    {
        try {
            return apiResponse($this->attributeService->getModelDetails($attribute));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param AttributeRequest $request
     * @param Attribute $attribute
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        try {
            $this->attributeService->update($request, $attribute);

            return apiResponse($this->attributeService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $attribute->label]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param AttributeRequest $request
     * @param Attribute $attribute
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AttributeRequest $request, Attribute $attribute)
    {
        try {
            $this->attributeService->destroy($request, $attribute);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $attribute->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
