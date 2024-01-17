<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\DataTables\PackagesDataTable;
use Corals\Modules\Marketplace\Http\Requests\PackageRequest;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Marketplace\Services\PackageService;
use Corals\Modules\Marketplace\Transformers\API\PackagePresenter;

class PackagesController extends APIBaseController
{
    protected $packageService;

    /**
     * PackagesController constructor.
     * @param PackageService $packageService
     * @throws \Exception
     */
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
        $this->packageService->setPresenter(new PackagePresenter());

        parent::__construct();
    }

    /**
     * @param PackageRequest $request
     * @param PackagesDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(PackageRequest $request, PackagesDataTable $dataTable)
    {
        $packages = $dataTable->query(new Package());

        return $this->packageService->index($packages, $dataTable);
    }

    /**
     * @param PackageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PackageRequest $request)
    {
        try {
            $package = $this->packageService->store($request, Package::class);
            return apiResponse($this->packageService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $package->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PackageRequest $request
     * @param Package $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PackageRequest $request, Package $package)
    {
        try {
            $this->packageService->update($request, $package);

            return apiResponse($this->packageService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $package->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param PackageRequest $request
     * @param Package $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PackageRequest $request, Package $package)
    {
        try {
            $this->packageService->destroy($request, $package);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $package->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
