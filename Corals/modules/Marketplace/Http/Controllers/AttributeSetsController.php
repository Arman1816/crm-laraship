<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\DataTables\AttributeSetsDataTable;
use Corals\Modules\Marketplace\Facades\Marketplace;
use Corals\Modules\Marketplace\Http\Requests\AttributeSetRequest;
use Corals\Modules\Marketplace\Models\AttributeSet;
use Corals\Modules\Marketplace\Services\AttributeSetService;

class AttributeSetsController extends BaseController
{
    protected $attributeSetService;

    public function __construct(AttributeSetService $attributeSetService)
    {
        $this->attributeSetService = $attributeSetService;

        $this->resource_url = config('marketplace.models.attribute_set.resource_url');
        $this->title = 'Marketplace::module.attribute_set.title';
        $this->title_singular = 'Marketplace::module.attribute_set.title_singular';

        parent::__construct();
    }

    /**
     * @param AttributeSetRequest $request
     * @param AttributeSetsDataTable $dataTable
     * @return mixed
     */
    public function index(AttributeSetRequest $request, AttributeSetsDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::attribute_sets.index');
    }

    /**
     * @param AttributeSetRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(AttributeSetRequest $request)
    {
        $attributeSet = new AttributeSet();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::attribute_sets.create_edit')->with(compact('attributeSet'));
    }

    /**
     * @param AttributeSetRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(AttributeSetRequest $request)
    {
        try {
            $this->attributeSetService->store($request, AttributeSet::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, AttributeSet::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param AttributeSetRequest $request
     * @param AttributeSet $attributeSet
     * @return AttributeSet
     */
    public function show(AttributeSetRequest $request, AttributeSet $attributeSet)
    {
        return $attributeSet;
    }

    /**
     * @param AttributeSetRequest $request
     * @param AttributeSet $attributeSet
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(AttributeSetRequest $request, AttributeSet $attributeSet)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $attributeSet->name])
        ]);

        return view('Marketplace::attribute_sets.create_edit')->with(compact('attributeSet'));
    }

    /**
     * @param AttributeSetRequest $request
     * @param AttributeSet $attributeSet
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function update(AttributeSetRequest $request, AttributeSet $attributeSet)
    {
        try {
            $this->attributeSetService->update($request, $attributeSet);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, AttributeSet::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param AttributeSetRequest $request
     * @param AttributeSet $attributeSet
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AttributeSetRequest $request, AttributeSet $attributeSet)
    {
        try {
            $this->attributeSetService->destroy($request, $attributeSet);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, AttributeSet::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function getSetAttributes(AttributeSetRequest $request, $modelId = null)
    {
        $sets_ids = request()->get('attribute_set_ids', "[]");

        $sets_ids = json_decode(urldecode($sets_ids));

        $modelClass = $request->get('model_class', []);

        $field_name = $request->get('field_name') ?: 'options';

        if (!is_array($sets_ids)) {
            return '';
        }

        $instance = null;


        $sets = AttributeSet::query()->whereIn('id', $sets_ids)->get();

        if (!is_null($modelId) && class_exists($modelClass)) {
            $instance = $modelClass::findByHash($modelId);
        }

        $fields = collect([]);

        foreach ($sets as $set) {
            $fields = $fields->merge($set->productAttributes);
        }

        $fields = $fields->unique('id');

        $attributesList = [];

        $input = '';

        foreach ($fields as $field) {
            $input .= Marketplace::renderAttribute($field, $instance, ['field_name' => $field_name]);
            $attributesList[$field->id] = $field->label;
        }

        return response()->json(['rendered_fields' => $input, 'attributes_list' => $attributesList]);
    }
}
