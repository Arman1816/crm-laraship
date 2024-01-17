<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\DataTables\PackagesDataTable;
use Corals\Modules\Marketplace\Http\Requests\PackageRequest;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Marketplace\Services\PackageService;
use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;
use Illuminate\Validation\ValidationException;

class PackagesController extends BaseController
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;

        $this->resource_url = config('marketplace.models.package.resource_url');
        $this->title = 'Marketplace::module.package.title';
        $this->title_singular = 'Marketplace::module.package.title_singular';
        parent::__construct();
    }

    /**
     * @param PackageRequest $request
     * @param PackagesDataTable $dataTable
     * @return mixed
     */
    public function index(PackageRequest $request, PackagesDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::packages.index');
    }

    /**
     * @param PackageRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(PackageRequest $request)
    {
        $package = new Package();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::packages.create_edit')->with(compact('package'));
    }

    /**
     * @param PackageRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(PackageRequest $request)
    {
        try {
            $this->packageService->store($request, Package::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Package::class, 'store');

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => trans('validation.message'),
                    'errors' => $exception->validator->getMessageBag()
                ], 422);
            }
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PackageRequest $request
     * @param Package $package
     * @return Package
     */
    public function show(PackageRequest $request, Package $package)
    {
        return $package;
    }

    /**
     * @param PackageRequest $request
     * @param Package $package
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(PackageRequest $request, Package $package)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::packages.create_edit')->with(compact('package'));
    }

    /**
     * @param PackageRequest $request
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PackageRequest $request, Package $package)
    {
        try {
            $this->packageService->update($request, $package);

            flash(trans('Corals::messages.success.updated', ['item' => $package->name]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Package::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param PackageRequest $request
     * @param Package $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PackageRequest $request, Package $package)
    {
        try {
            $name = $package->name;
            $this->packageService->destroy($request, $package);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $name])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Package::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function getPackageTemplateCode(PackageRequest $request, $code)
    {
        return response()->json(ListOfValues::getLOVByCode($code, null, true));
    }
}
