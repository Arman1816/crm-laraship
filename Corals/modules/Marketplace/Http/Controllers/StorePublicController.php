<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\PublicBaseController;
use Corals\Modules\CMS\Traits\SEOTools;
use Corals\Modules\Marketplace\Facades\Shop;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StorePublicController extends PublicBaseController
{
    use SEOTools;

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $item = [
            'title' => 'Stores',
            'meta_description' => 'Marketplace Stores',
            'url' => url('stores'),
            'type' => 'store',
            'image' => \Settings::get('site_logo'),
            'meta_keywords' => 'shop,marketplace,products'
        ];
        $this->setSEO((object)$item);

        $result = $this->showStores($request);
        return view('templates.stores')->with($result);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showStore(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (!$store) {
            abort(404);
        }

        if ($request->is('*return-policy')) {
            return $store->return_policy;
        }

        $item = [
            'title' => 'Shop',
            'meta_description' => 'Marketplace Shop',
            'url' => url('shop'),
            'type' => 'shop',
            'image' => \Settings::get('site_logo'),
            'meta_keywords' => 'shop,marketplace,products'
        ];

        $this->setSEO((object)$item);

        $result = $this->showStores($request, $store);

        $result['store'] = $store;

        return view('templates.store')->with($result);
    }


    public function autoCompleteProductSearch(Request $request)
    {
        //$request->merge(['search' => $request->input('q')]);

        $stores = Shop::getStores($request);
        $results = [];
        foreach ($stores as $store) {
            $results[] = [
                'value' => $stores->present('id'),
                'name' => $stores->name,
                'name_hyperlink' => $stores->present('name'),
                'image' => $stores->image,
                'url' => $stores->getShowURL()

            ];
        }
        return \Response::json([
            'results' => $results,
            'status' => 'success'
        ]);
    }

    private function showStores($request, $store = null)
    {
        $layout = $request->get('layout', 'list');

        $stores = \Corals\Modules\Marketplace\Facades\Store::getStores($request);


        $storeText = null;

        if ($request->has('search') && !empty($request->input('search'))) {
            $storeText = trans('Marketplace::labels.shop.search_results_for',
                ['search' => strip_tags($request->get('search'))]);
        }

        $sortOptions = trans(config('marketplace.models.store.sort_options'));


        if (\Settings::get('marketplace_rating_enable') == "true") {
            $sortOptions['average_rating'] = trans('Marketplace::attributes.product.average_rating');
        }

        return compact('layout', 'stores', 'storeText', 'sortOptions');
    }

    public function show(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            abort(404);
        }

        $product->visitors_count += 1;

        $product->save();

        $categories = join(',', $product->activeCategories->pluck('name')->toArray());
        $tags = join(',', $product->activeTags->pluck('name')->toArray());

        $item = [
            'title' => $product->name,
            'meta_description' => \Str::limit(strip_tags($product->description), 500),
            'url' => url('shop/' . $product->slug),
            'type' => 'product',
            'image' => $product->image,
            'meta_keywords' => $categories . ',' . $tags
        ];

        $this->setSEO((object)$item);

        view()->share('product', $product);

        Shop::trackUserAction($product, 'view_product');

        return view('templates/product_single')->with(compact('product'));
    }

    public function contact(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'store_email' => 'required|email',
            'message' => 'required'
        ]);

        $data = $request->all();

        Mail::send('emails.contact', $data, function ($message) {
            $message->from(\Request::get('email'), 'Contact message for: ' . \Request::get('product_name'));
            $message->to(\Request::get('store_email'));
        });

        return \Response::json([
            'message' => trans('CMS::labels.message.email_sent_success'),
            'class' => 'alert-success',
            'level' => 'success'
        ]);
    }

}
