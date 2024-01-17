<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Marketplace\Facades\Shop;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Transformers\API\ProductPresenter;
use Corals\Modules\Marketplace\Transformers\API\SKUPresenter;
use Corals\Modules\Utility\Transformers\API\Comment\CommentPresenter;
use Corals\Modules\Utility\Transformers\API\Rating\RatingPresenter;
use Illuminate\Http\Request;

class ShopController extends APIBaseController
{
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->corals_middleware_except = array_merge($this->corals_middleware_except,
            ['productsList', 'singleProduct']);
        parent::__construct();
    }

    /**
     * @param $permission
     */
    private function canAccess($permission)
    {
        if (!user()->hasPermissionTo($permission)) {
            abort(403, 'Forbidden!!');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsList(Request $request)
    {
        try {
            $products = Shop::getProducts($request);

            return (new ProductPresenter())->present($products);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleProduct(Request $request, Product $product)
    {
        try {
            $product->setPresenter(new ProductPresenter());

            $skus = (new SKUPresenter())->present($product->activeSKU)['data'];

            $ratings = $product->ratings()->paginate(null, ['*'], 'rate-page');
            $comments = $product->comments()->paginate(null, ['*'], 'comment-page');

            $ratingsList = (new RatingPresenter())->present($ratings);
            $commentsList = (new CommentPresenter())->present($comments);

            $singleProductResponse = [
                'product' => $product->presenter(),
                'ratings' => $ratingsList,
                'comments' => $commentsList,
                'skus' => $skus
            ];

            return apiResponse($singleProductResponse);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function settings(Request $request)
    {
        try {
            $this->canAccess('Marketplace::settings.access');

            $settings = config('marketplace.site_settings');

            $settingsList = [];

            foreach ($settings as $setting_key => $setting_items) {
                foreach ($setting_items as $key => $setting) {
                    $setting_concat = 'marketplace_' . strtolower($setting_key) . '_' . $key;

                    $settingsList[$setting_key][$key] = \Settings::get($setting_concat);
                }
            }
            return apiResponse($settingsList);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
