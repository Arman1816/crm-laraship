<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Marketplace\DataTables\StoresDataTable;
use Corals\Modules\Marketplace\Http\Requests\EnrollRequest;
use Corals\Modules\Marketplace\Http\Requests\StoreRequest;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Modules\Payment\Facades\Payments;
use Corals\Modules\Payment\Payment;
use Corals\Modules\Subscriptions\Models\Product;
use Corals\Settings\Facades\Settings;
use Illuminate\Http\Request;

class StoresController extends BaseController
{
    protected $excludedRequestParams = ['thumbnail', 'cover_photo', 'clear_cover_photo', 'clear_logo'];

    public function __construct()
    {
        $this->resource_url = config('marketplace.models.store.resource_url');

        $this->title = 'Marketplace::module.store.title';
        $this->title_singular = 'Marketplace::module.store.title_singular';

        parent::__construct();
    }

    /**
     * @param StoreRequest $request
     * @param StoresDataTable $dataTable
     * @return mixed
     */
    public function index(StoreRequest $request, StoresDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::stores.index');
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(StoreRequest $request)
    {
        $store = new Store();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Marketplace::stores.create_edit')->with(compact('store'));
    }

    /**
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->except($this->excludedRequestParams);

            $store = Store::create($data);

            $this->handleStorePhotos($request, $store);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Store::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param StoreRequest $request
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(StoreRequest $request, Store $store)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $store->getIdentifier()])
        ]);

        $this->setViewSharedData(['edit_url' => $this->resource_url . '/' . $store->hashed_id . '/edit']);

        return view('Marketplace::stores.show')->with(compact('store'));
    }

    /**
     * @param StoreRequest $request
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(StoreRequest $request, Store $store)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $store->getIdentifier()])
        ]);

        return view('Marketplace::stores.create_edit')->with(compact('store'));
    }

    /**
     * @param StoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(StoreRequest $request, Store $store)
    {
        try {
            $data = $request->except($this->excludedRequestParams);

            if (\Store::isStoreAdmin()) {
                $data['is_featured'] = \Arr::get($data, 'is_featured', false);
            }

            $store->update($data);

            $this->handleStorePhotos($request, $store);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Store::class, 'update');
        }
        return redirectTo(\URL::previous());
    }

    protected function handleStorePhotos($request, $store)
    {
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
    }

    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);
            switch ($action) {
                case 'delete':
                    foreach ($selection as $selection_id) {
                        $store = Store::findByHash($selection_id);
                        $store_request = new StoreRequest;
                        $store_request->setMethod('DELETE');
                        $this->destroy($store_request, $store);
                    }
                    $message = [
                        'level' => 'success',
                        'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
                    ];
                    break;
                case 'active' :
                    foreach ($selection as $selection_id) {
                        $store = Store::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $store->update([
                                'status' => 'active'
                            ]);
                            $store->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;

                case 'inActive' :
                    foreach ($selection as $selection_id) {
                        $store = Store::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $store->update([
                                'status' => 'inactive'
                            ]);
                            $store->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;

                case 'isFeatured' :
                    foreach ($selection as $selection_id) {
                        $store = Store::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $store->update([
                                'is_featured' => true
                            ]);
                            $store->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;
                case 'unFeatured' :
                    foreach ($selection as $selection_id) {
                        $store = Store::findByHash($selection_id);
                        if (user()->can('Marketplace::store.update')) {
                            $store->update([
                                'is_featured' => false
                            ]);
                            $store->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Marketplace::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Marketplace::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, Product::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param StoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(StoreRequest $request, Store $store)
    {
        try {
            $store->delete();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Store::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    public function enroll(Request $request)
    {
        try {
            $subscription_required = \Settings::get('marketplace_general_vendor_require_subscription', false);
            if ($subscription_required) {
                $marketplace_subscription_product = \Settings::get('marketplace_general_subscription_product', '');
                if ($marketplace_subscription_product) {
                    $product = Product::find($marketplace_subscription_product);
                    if ($product) {
                        return redirectTo('subscriptions/products/' . $product->hashed_id);
                    }
                }
                flash(trans('Marketplace::exception.store.invalid_subscription_product'))->error();
                return redirectTo('dashboard');
            } else {
                $this->setViewSharedData(['title_singular' => trans('Marketplace::labels.store.enroll')]);

                return view('Marketplace::stores.enroll');
            }
        } catch (\Exception $exception) {
            log_exception($exception, Store::class, 'update');
        }
    }

    /**
     * @param EnrollRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function doEnroll(EnrollRequest $request)
    {
        try {
            \Store::createStore(user());

            flash(trans('Marketplace::messages.vendor.success.enroll'))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Store::class, 'Enroll');
        }

        return redirectTo('marketplace/store/settings');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings(Request $request)
    {
        $this->setViewSharedData(['title_singular' => 'Store Settings']);

        $settings = config('marketplace.store_settings');
        $isShippingPerProduct = Settings::get('marketplace_shipping_enable_shipping_per_product');

        if (!$isShippingPerProduct) {
            unset($settings['Shipping']['is_shipping_per_product']);
        }

        if (!Settings::get('marketplace_shipping_shippo_per_store', true)) {
            foreach ($settings['Shipping'] as $key => $value) {
                if (in_array($key, ['is_shipping_per_product', 'shippable_countries', 'preferred_providers'])) {
                    continue;
                }

                unset($settings['Shipping'][$key]);
            }
        }

        $store = \Store::getVendorStore();

        $gateways = Payments::getAvailableGateways('support_connect_account');

        $connectGateways = [];

        foreach ($gateways as $gateway => $gateway_title) {
            $paymentGateway = Payment::create($gateway);

            $paymentGateway->setAuthentication();

            if ($paymentGateway->getSettings('connect_enabled')) {
                $gatewayStatus = user()->getGatewayStatus($gateway, 'AccountConnect', true)->first();
                $connectGateways[$gateway] = [
                    'gateway_title' => $gateway_title,
                    'connect_url' => method_exists($paymentGateway, 'getConnectAccountAuthorizeURL') ?
                        $paymentGateway->getConnectAccountAuthorizeURL() : null,

                    'gateway' => optional($gatewayStatus)->gateway,
                    'status' => optional($gatewayStatus)->status,
                    'account_id' => optional($gatewayStatus)->object_reference,
                    'gateway_status_id' => optional($gatewayStatus)->id,

                    'connect_settings' => method_exists($paymentGateway, 'getConnectAccountSettings') ?
                        $paymentGateway->getConnectAccountSettings($gatewayStatus) : '',
                ];
            }
        }

        return view('Marketplace::stores.settings')->with(compact('settings', 'store', 'connectGateways'));
    }

    public function recheckConnectedAccountStatus(Request $request, $gateway, $accountId)
    {
        try {
            $payments = new \Corals\Modules\Payment\Classes\Payments($gateway);

            $payments->fetchConnectAccount($accountId);
            $message = [
                'level' => 'success',
                'message' => trans('Marketplace::labels.connect_accounts.account_fetch_submitted')
            ];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function saveSettings(Request $request)
    {
        try {
            $settings = $request->except('_token');
            $store = \Store::getVendorStore();

            foreach ($settings as $key => $value) {
                list($setting_key, $cast) = explode('|', $key);
                $store->updateSetting($setting_key, $value, $cast);
            }

            flash(trans('Corals::messages.success.saved', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, 'marketplaceSettings', 'savedSettings');
        }

        return redirectTo('marketplace/store/settings');
    }

    /**
     * Set locale if it's allowed.
     * @param Request $request
     * @param $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setStore(Request $request, $store)
    {
        session()->put('current_store', $store);
        return redirect()->back();
    }
}
