<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\CMS\DataTables\DownloadsDataTable;
use Corals\Modules\CMS\Http\Requests\DownloadRequest;
use Corals\Modules\CMS\Models\Download;
use Corals\Modules\CMS\Services\DownloadService;
use Illuminate\Http\Request;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadsController extends BaseController
{
    protected $downloadService;

    public function __construct(DownloadService $downloadService)
    {
        $this->downloadService = $downloadService;

        $this->resource_url = config('cms.models.download.resource_url');
        $this->title = 'CMS::module.download.title';
        $this->title_singular = 'CMS::module.download.title_singular';

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['downloadFile']);

        parent::__construct();
    }

    /**
     * @param DownloadRequest $request
     * @param DownloadsDataTable $dataTable
     * @return mixed
     */
    public function index(DownloadRequest $request, DownloadsDataTable $dataTable)
    {
        return $dataTable->render('CMS::download.index');
    }

    /**
     * @param DownloadRequest $request
     * @return $this
     */
    public function create(DownloadRequest $request)
    {
        $download = new Download();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('CMS::download.create_edit')->with(compact('download'));
    }

    /**
     * @param DownloadRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(DownloadRequest $request)
    {
        try {
            $this->downloadService->store($request, Download::class, ['author_id' => user()->id]);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Download::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param DownloadRequest $request
     * @param Download $download
     * @return Download
     */

    public function show(DownloadRequest $request, Download $download)
    {
        return $download;
    }

    /**
     * @param DownloadRequest $request
     * @param Download $download
     * @return $this
     */
    public function edit(DownloadRequest $request, Download $download)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $download->title])]);

        return view('CMS::download.create_edit')->with(compact('download'));
    }

    /**
     * @param DownloadRequest $request
     * @param  $download
     * @return $this
     */
    public function update(DownloadRequest $request, Download $download)
    {
        try {
            $this->downloadService->update($request, $download);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Download::class, 'update');
        }

        return redirectTo($this->resource_url);
    }


    /**
     * @param Request $request
     * @param $hashed_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile(Request $request, $hashed_id)
    {
        $id = hashids_decode($hashed_id);

        $media = Media::findOrfail($id);

        $downloads = intval($media->getCustomProperty('downloads_count', 0)) + 1;

        $media->setCustomProperty('downloads_count', $downloads);

        $media->save();

        return response()->download(storage_path($media->getUrl()));
    }

    public function destroy(DownloadRequest $request, Download $download)
    {
        try {
            $this->downloadService->destroy($request, $download);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $download->title])];
        } catch (\Exception $exception) {
            log_exception($exception, Download::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
