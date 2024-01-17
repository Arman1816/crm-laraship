<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Http\Requests\MarketplaceImportRequest;
use Corals\Modules\Marketplace\Jobs\HandleBrandsImportFile;
use Corals\Modules\Marketplace\Jobs\HandleCategoriesImportFile;
use Corals\Modules\Marketplace\Jobs\HandleProductsImportFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\Csv\CannotInsertRecord;
use League\Csv\Reader;
use League\Csv\Writer;


class MarketplaceImportController extends BaseController
{
    protected $importHeaders;
    protected $importTarget;

    public function __construct()
    {
        $segments = request()->segments();

        $target = $segments[1] ?? "";

        if (!$target) {
            return;
        }

        $target = Str::singular($target);

        $this->importTarget = $target;

        $this->resource_url = config("marketplace.models.$target.resource_url");

        $this->importHeaders = trans("Marketplace::import.$target-headers");

        $this->middleware(function ($request, \Closure $next) use ($target) {
            $model = 'Corals\\Modules\\Marketplace\\Models\\' . ucfirst($target);

            abort_if(user()->cannot('create', $model), 403, 'Unauthorized');

            return $next($request);
        });

        parent::__construct();
    }

    /**
     * @param MarketplaceImportRequest $request
     * @return Application|Factory|View
     */
    public function getImportModal(MarketplaceImportRequest $request)
    {
        $headers = $this->importHeaders;
        $target = $this->importTarget;

        return view('Marketplace::partials.import_modal')
            ->with(compact('headers', 'target'));
    }

    /**
     * @param MarketplaceImportRequest $request
     * @throws CannotInsertRecord
     */
    public function downloadImportSample(MarketplaceImportRequest $request)
    {
        //we create the CSV into memory
        $csv = Writer::createFromFileObject(new \SplTempFileObject())->setDelimiter(config('corals.csv_delimiter',
            ','));

        //we insert the CSV header
        $csv->insertOne(array_keys($this->importHeaders));

        $target = Str::plural($this->importTarget, 0);

        $csv->output(sprintf('marketplace_%s_%s.csv', $target, now()->format('Y-m-d-H-i')));

        die;
    }

    /**
     * @param MarketplaceImportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImportFile(MarketplaceImportRequest $request)
    {
        try {
            // store file in temp folder
            $file = $request->file('file');

            $importsPath = storage_path('app/marketplace/imports');

            $fileName = sprintf("%s_%s", Str::random(), $file->getClientOriginalName());

            $fileFullPath = $importsPath . '/' . $fileName;
            $file->move($importsPath, $fileName);

            $reader = Reader::createFromPath($fileFullPath, 'r')
                ->setDelimiter(config('corals.csv_delimiter', ','))
                ->setHeaderOffset(0);

            $header = $reader->getHeader();

            // validate file headers
            if (count(array_diff(array_keys($this->importHeaders), $header))) {
                unset($reader);
                @unlink($fileFullPath);
                throw new \Exception(trans('Marketplace::import.exceptions.invalid_headers'));
            }

            $images_root = $request->get('images_root');
            $clearExistingImages = $request->get('clear_images', false);
            switch ($this->importTarget) {
                case 'product':
                    // dispatch import job
                    $storeId = $request->get('store_id');

                    if (!$storeId) {
                        $store = Store::getVendorStore();

                        if (!$store) {
                            throw new \Exception('Unable to specify Store to attach object to');
                        }

                        $storeId = $store->id;
                    }

                    $this->dispatch(
                        new HandleProductsImportFile(
                            $fileFullPath,
                            $images_root,
                            $clearExistingImages,
                            $storeId, user())
                    );
                    break;
                case 'category':
                    $this->dispatch(new HandleCategoriesImportFile($fileFullPath, $images_root, user()));
                    break;
                case 'brand':
                    $this->dispatch(new HandleBrandsImportFile($fileFullPath, $images_root, user()));
                    break;
            }

            return response()->json([
                'level' => 'success',
                'action' => 'closeModal',
                'message' => trans('Marketplace::import.messages.file_uploaded')
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'level' => 'error',
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
