<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\Facades\Shop;
use Corals\Modules\Marketplace\Models\Product;
use Illuminate\Http\Request;

class ShopController extends BaseController
{
    /**
     * CartController constructor.
     */
    public function __construct()
    {
        $this->title = 'Marketplace::module.shop.title';
        $this->title_singular = 'Marketplace::module.shop.title';

        parent::__construct();
    }

    /**
     * @param $permission
     */
    private function canAccess($permission)
    {
        if (!user()->hasPermissionTo($permission)) {
            abort(403);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->canAccess('Marketplace::shop.access');

        $grid_items = Shop::getProducts($request);

        $grid_item_view = 'Marketplace::shop.grid_item';

        return view('Marketplace::shop.grid')->with(compact('grid_item_view', 'grid_items'));
    }

    public function show(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            abort(404);
        }
        $this->canAccess('Marketplace::shop.access');

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.show_title', ['title' => $product->name])]);

        view()->share('product', $product);

        Shop::trackUserAction($product, 'view_product');

        return view('Marketplace::shop.show')->with(compact('product'));
    }

    public function settings(Request $request)
    {
        $this->canAccess('Marketplace::settings.access');

        $this->setViewSharedData(['title_singular' => 'marketplace Settings']);

        $settings = config('marketplace.site_settings');

        return view('Marketplace::shop.settings')->with(compact('settings'));
    }

    public function saveSettings(Request $request)
    {
        try {
            $this->canAccess('Marketplace::settings.access');

            $settings = $request->except('_token');

            foreach ($settings as $key => $value) {
                list($setting_key, $cast) = explode('|', $key);
                switch ($cast) {
                    case 'array':
                        $cast = 'SELECT';
                        break;
                }

                \Settings::set($setting_key, $value, 'Marketplace', $cast);
            }

            flash(trans('Corals::messages.success.saved', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, 'marketplaceSettings', 'savedSettings');
        }

        return redirectTo('marketplace/settings');
    }
}
