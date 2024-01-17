<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\Http\Requests\StoreRequest;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Modules\Marketplace\Services\StoreService;
use Corals\Modules\Marketplace\Transformers\API\StorePresenter;
use Illuminate\Http\Request;

class StoresController extends APIBaseController
{
    protected $storeService;
    protected $excludedRequestParams = ['_method', 'thumbnail', 'cover_photo', 'clear_cover_photo', 'clear_logo'];

    /**
     * TagsController constructor.
     * @param StoreService $storeService
     * @throws \Exception
     */
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->storeService->setPresenter(new StorePresenter());

        parent::__construct();
    }

    public function getSettings(Request $request)
    {
        try {
            $store = \Store::getVendorStore();

            if (!$store) {
                abort(404, 'Not Found!!');
            }

            return apiResponse((new StorePresenter())->present($store));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param StoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSettings(StoreRequest $request, Store $store)
    {
        try {
            $storeAttr = ['name', 'short_description', 'parking_domain'];

            $storeData = $request->only($storeAttr);

            $settings = $request->except(array_merge($storeAttr, $this->excludedRequestParams));

            $store->update($storeData);

            if ($request->has('clear_logo') || $request->hasFile('thumbnail')) {
                $store->clearMediaCollection($store->mediaCollectionName);
            }

            if ($request->has('clear_cover_photo') || $request->hasFile('cover_photo')) {
                $store->clearMediaCollection($store->coverPhotoMediaCollectionName);
            }

            if ($request->hasFile('thumbnail')) {
                $store->addMedia($request->file('thumbnail'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($store->mediaCollectionName);
            }

            if ($request->has('clear_cover') || $request->hasFile('cover_photo')) {
                $store->clearMediaCollection($store->coverPhotoMediaCollectionName);
            }

            if ($request->hasFile('cover_photo')) {
                $store->addMedia($request->file('cover_photo'))
                    ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                    ->toMediaCollection($store->coverPhotoMediaCollectionName);
            }

            foreach ($settings as $key => $value) {
                list($setting_key, $cast) = explode('|', $key);
                $store->updateSetting($setting_key, $value, $cast);
            }

            return apiResponse((new StorePresenter())->present($store), trans('Corals::messages.success.saved', ['item' => trans('Marketplace::module.store.title_singular')]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
