<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\PublicBaseController;
use Corals\Modules\CMS\Traits\SEOTools;
use Corals\Modules\Marketplace\Facades\Shop;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Models\Store;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShopPublicController extends PublicBaseController
{
    use SEOTools;

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $item = [
            'title' => 'Shop',
            'meta_description' => 'Marketplace Shop',
            'url' => url('shop'),
            'type' => 'shop',
            'image' => \Settings::get('site_logo'),
            'meta_keywords' => 'shop,marketplace,products'
        ];

        $this->setSEO((object)$item);

        $result = $this->showPorducts($request);

        return view('index')->with($result);
    }

    public function front_index(){
        $item = [
            'title' => 'Shop',
            'meta_description' => 'Marketplace Shop',
            'url' => url('shop'),
            'type' => 'shop',
            'image' => \Settings::get('site_logo'),
            'meta_keywords' => 'shop,marketplace,products'
        ];
        $this->setSEO((object)$item);

        $result = $this->showPorducts($request);

        
        return view('index')->with($result);
       
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Application|Factory|View
     */
    public function showStore(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->active()->first();

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

        $result = $this->showPorducts($request, $store);

        $result['store'] = $store;

        return view('templates.store')->with($result);
    }


    public function autoCompleteProductSearch(Request $request, $store = null)
    {
        //$request->merge(['search' => $request->input('q')]);

        $products = Shop::getProducts($request, $store);
        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'value' => $product->present('id'),
                'name' => $product->name,
                'name_hyperlink' => $product->present('name'),
                'image' => $product->image,
                'url' => $product->getShowURL()

            ];
        }
        return \Response::json([
            'results' => $results,
            'status' => 'success'
        ]);
    }

    private function showPorducts($request, $store = null)
    {
        $layout = $request->get('layout', 'grid');

        $products = Shop::getProducts($request, $store);


        $shopText = null;

        if ($request->has('search') && !empty($request->input('search'))) {
            $shopText = trans('Marketplace::labels.shop.search_results_for',
                ['search' => strip_tags($request->get('search'))]);
        }

        $sortOptions = trans(config('marketplace.models.shop.sort_options'));


        if (\Settings::get('marketplace_rating_enable') == "true") {
            $sortOptions['average_rating'] = trans('Marketplace::attributes.product.average_rating');
        }

        return compact('layout', 'products', 'shopText', 'sortOptions');
    }

    public function show(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->active()->first();

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

    public function getSKUPricePerQuantity(Request $request, $slug)
    {
        /**
         * @var Product $product
         */
        $product = Product::where('slug', $slug)->active()->first();

        if (!$product) {
            abort(404);
        }

        $data = $request->all();

        $qty = $request->get('quantity');
        //validate qty

        $pricePerQuantity = $product->price_per_quantity;

        /**
         * @var SKU
         */
        $sku = null;

        if ($request->has('options')) {
            $options = data_get($data, 'options');

            $skus = SKU::query()
                ->where('marketplace_sku.product_id', $product->id)
                ->select('marketplace_sku.*');

            $attributesColumnMapping = $this->attributesColumnMapping();

            foreach ($options as $key => $value) {
                $skus = $skus->join("marketplace_sku_options as attribute_$key", "attribute_$key.sku_id", '=',
                    'marketplace_sku.id');

                $value = isset($attributesColumnMapping[$key]['operation']) && $attributesColumnMapping[$key]['operation'] == 'like' ? '%' . $value . '%' : $value;
                if (is_array($value)) {
                    $skus = $skus->where("attribute_$key." . $attributesColumnMapping[$key]['column'] ?? 'string_value',
                        $value);
                } else {
                    $skus = $skus->where("attribute_$key." . $attributesColumnMapping[$key]['column'] ?? 'string_value',
                        $attributesColumnMapping[$key]['operation'] ?? '=', $value);
                }
            }

            $sku = $skus->first();
        } elseif ($request->has('sku_hash')) {
            $sku = SKU::findByHash($data['sku_hash']);
        }
        $price = $product->price;

        if (!$sku) {
            return response()->json([
                'price' => $price,
                'message' => HtmlElement('div', ['class' => 'alert alert-danger'],
                    trans('Marketplace::labels.shop.not_available'))
            ]);
        }

        if (!$sku->checkInventory($qty)) {
            return response()->json([
                'price' => $price,
                'message' => HtmlElement('div', ['class' => 'alert alert-warning'],
                    trans('Marketplace::labels.shop.out_stock'))
            ]);
        }

        if (!$pricePerQuantity || $product->offers) {
            return response()->json([
                'sku_hash' => $sku->hashed_id,
                'price' => \Payments::currency($sku->price),
            ]);
        }

        $price = data_get($sku->getPriceWithOffers($qty), 'price');

        $originalPrice = $sku->price;


        return response()->json([
            'sku_hash' => $sku->hashed_id,
            'price' => \Payments::currency($price),
            'originalPrice' => \Payments::currency($originalPrice)
        ]);
    }

    private function attributesColumnMapping()
    {
        $attributes = Attribute::query()->get();


        $attributesColumnMapping = [];

        foreach ($attributes as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                case 'text':
                case 'date':
                    $attributesColumnMapping[$attribute->id]['column'] = 'string_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = 'like';
                    break;
                case 'textarea':
                    $attributesColumnMapping[$attribute->id]['column'] = 'text_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = 'like';
                    break;
                case 'number':
                case 'select':
                case 'multi_values':
                case 'radio':
                    $attributesColumnMapping[$attribute->id]['column'] = 'number_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = '=';
                    break;
                default:
                    $attributesColumnMapping[$attribute->id]['column'] = 'string_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = '=';
            }
        }

        return $attributesColumnMapping;
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
