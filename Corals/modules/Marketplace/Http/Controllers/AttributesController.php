<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\DataTables\AttributesDataTable;
use Corals\Modules\Marketplace\Http\Requests\AttributeRequest;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Services\AttributeService;
use Illuminate\Http\Request;

class AttributesController extends BaseController
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;

        $this->resource_url = config('marketplace.models.attribute.resource_url');
        $this->title = 'Marketplace::module.attribute.title';
        $this->title_singular = 'Marketplace::module.attribute.title_singular';

        parent::__construct();
    }

    /**
     * @param AttributeRequest $request
     * @param AttributesDataTable $dataTable
     * @return mixed
     */
    public function index(AttributeRequest $request, AttributesDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::attributes.index');
    }

    /**
     * @param AttributeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(AttributeRequest $request)
    {
        $attribute = new Attribute();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('Marketplace::attributes.create_edit')->with(compact('attribute'));
    }

    /**
     * @param AttributeRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(AttributeRequest $request)
    {
        try {
            $this->attributeService->store($request, Attribute::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Attribute::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param AttributeRequest $request
     * @param Attribute $attribute
     * @return Attribute
     */
    public function show(AttributeRequest $request, Attribute $attribute)
    {
        return $attribute;
    }

    /**
     * @param AttributeRequest $request
     * @param Attribute $attribute
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(AttributeRequest $request, Attribute $attribute)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $attribute->label])]);

        return view('Marketplace::attributes.create_edit')->with(compact('attribute'));
    }

    /**
     * @param AttributeRequest $request
     * @param Attribute $attribute
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        try {
            $this->attributeService->update($request, $attribute);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Attribute::class, 'update');
        }

        return redirectTo($this->resource_url);
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

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Attribute::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function renderSelectOptions(Request $request)
    {
        try {
            return $this->attributeService->renderSelectOptions($request);
        } catch (\Exception $exception) {
            log_exception($exception, Attribute::class, 'destroy');
            return response()->json(['level' => 'error', 'message' => $exception->getMessage()]);

        }

    }
}
