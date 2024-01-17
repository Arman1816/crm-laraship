<?php

namespace Corals\Modules\Foo\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Foo\DataTables\BarsDataTable;
use Corals\Modules\Foo\Http\Requests\BarRequest;
use Corals\Modules\Foo\Models\Bar;
use Corals\Modules\Foo\Services\BarService;
use Corals\Modules\Foo\Transformers\API\BarPresenter;

class BarsController extends APIBaseController
{
    protected $barService;

    /**
     * BarsController constructor.
     * @param BarService $barService
     * @throws \Exception
     */
    public function __construct(BarService $barService)
    {
        $this->barService = $barService;
        $this->barService->setPresenter(new BarPresenter());

        parent::__construct();
    }

    /**
     * @param BarRequest $request
     * @param BarsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(BarRequest $request, BarsDataTable $dataTable)
    {
        $bars = $dataTable->query(new Bar());

        return $this->barService->index($bars, $dataTable);
    }

    /**
     * @param BarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BarRequest $request)
    {
        try {
            $bar = $this->barService->store($request, Bar::class);
            return apiResponse($this->barService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $bar->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param BarRequest $request
     * @param Bar $bar
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BarRequest $request, Bar $bar)
    {
        try {
            return apiResponse($this->barService->getModelDetails($bar));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param BarRequest $request
     * @param Bar $bar
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BarRequest $request, Bar $bar)
    {
        try {
            $this->barService->update($request, $bar);

            return apiResponse($this->barService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $bar->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param BarRequest $request
     * @param Bar $bar
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BarRequest $request, Bar $bar)
    {
        try {
            $this->barService->destroy($request, $bar);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $bar->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
