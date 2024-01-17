<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\AttributesDataTable;
use Corals\Modules\Marketplace\Http\Requests\AttributeRequest;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Services\AttributeService;
use Corals\Modules\Marketplace\Transformers\API\AttributePresenter;

class AttributesController extends APIBaseController
{
    protected $attributeService;

    /**
     * AttributesController constructor.
     * @param AttributeService $attributeService
     * @throws \Exception
     */
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
     * @throws \Exception
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

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $attribute->label]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
