<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\PublicBaseController;
use Corals\Modules\CMS\Traits\SEOTools;
use Corals\Modules\Marketplace\Http\Requests\AddToCartRequest;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Models\UserCart;
use Corals\Settings\Facades\Settings;
use Illuminate\Http\Request;

class CartPublicController extends PublicBaseController
{
    use SEOTools;

    /**
     * CartController constructor.
     */
    public function __construct()
    {
        $this->title = 'Marketplace::module.cart.title';
        $this->title_singular = 'Marketplace::module.cart.title';
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $item = [
            'title' => 'Cart',
            'meta_description' => 'Shopping Cart',
            'url' => url('cart'),
            'type' => 'cart'
        ];

        $this->setSEO((object)$item);

        return view('templates.cart');
    }

    /**
     * @param Request $request
     * @param $itemhash
     * @return \Illuminate\Http\JsonResponse
     */
    public function setQuantity(Request $request, $itemhash)
    {
        $data = [];

        try {
            $data = $request->json()->all();
            $action = isset($data['action']) ? $data['action'] : '';
            $cartItem = \ShoppingCart::find(['itemHash' => $itemhash]);
            $sku = $cartItem->id;
            $cart = \ShoppingCart::setInstance($sku->product->store->id);

            if ($action == "increaseQuantity" && $itemhash) {
                $quantity = $cartItem->qty + 1;
                if (($quantity > $sku->allowed_quantity) && ($sku->allowed_quantity > 0)) {
                    $cart->updateItem($itemhash, 'qty', $sku->allowed_quantity);
                    $message = [
                        'level' => 'warning',
                        'message' => trans('Marketplace::exception.cart.item_limited_per_order',
                            ['quantity' => $sku->allowed_quantity])
                    ];
                    $data['quantity'] = $sku->allowed_quantity;
                } else {
                    $sku->checkInventory($quantity, true);
                    $item = $cart->increment($itemhash);
                    $message = [
                        'level' => 'success',
                        'message' => trans(trans('Marketplace::labels.cart.item_has_been_update'))
                    ];
                    $data['quantity'] = $cartItem->qty;
                }
            } else {
                if ($action == "decreaseQuantity" && $itemhash) {
                    $cartItem = $cart->decrement($itemhash);

                    if (!$cartItem) {
                        $action = "removeItem";
                        $message = [
                            'level' => 'success',
                            'message' => trans(trans('Marketplace::labels.cart.item_has_been_delete'))
                        ];
                    } else {
                        $message = [
                            'level' => 'success',
                            'message' => trans(trans('Marketplace::labels.cart.item_has_been_update'))
                        ];
                        $data['quantity'] = $cartItem->qty;
                    }
                } else {
                    if ($action == "removeItem" && $itemhash) {
                        $cart->removeItem($itemhash);
                        $message = [
                            'level' => 'success',
                            'message' => trans(trans('Marketplace::labels.cart.item_has_been_delete'))
                        ];
                    } else {
                        if (isset($request->quantity)) {
                            $quantity = $request->quantity;

                            if (($quantity > $sku->allowed_quantity) && ($sku->allowed_quantity > 0)) {
                                $quantity = $sku->allowed_quantity;
                                $message = [
                                    'level' => 'warning',
                                    'message' => trans('Marketplace::exception.cart.item_limited_per_order',
                                        ['quantity' => $sku->allowed_quantity])
                                ];
                            } else {
                                $sku->checkInventory($request->quantity, true);
                                $message = [
                                    'level' => 'success',
                                    'message' => trans(trans('Marketplace::labels.cart.item_has_been_update'))
                                ];
                            }

                            $cart->updateItem($itemhash, 'qty', $quantity);
                            $data['quantity'] = $quantity;
                        }
                    }
                }
            }

            $message['itemhash'] = $itemhash;

            $quantity = $cartItem->qty;

            $priceWithOffers = $sku->getPriceWithOffers($quantity, true);

            $price = data_get($priceWithOffers, 'price');

            $subtotal = data_get($priceWithOffers, 'subtotal');

            $cart->updateItem($itemhash, 'price', $price);
            $cart->updateItem($itemhash, 'qty', $quantity);
            $cart->updateItem($itemhash, 'subtotal', $subtotal);

            $data['item_total'] = $cartItem->subtotal();
            $data['action'] = $action;
            $data['sub_total'] = \ShoppingCart::subTotalAllInstances();
            $data['tax_total'] = \ShoppingCart::taxTotalAllInstances();
            $data['total_discount'] = \ShoppingCart::totalDiscountAllInstances();
            $data['total'] = \ShoppingCart::totalAllInstances();
            $data['cart_count'] = \ShoppingCart::countAllInstances();

            if (count(\ShoppingCart::getAllInstanceItems()) > 0) {
                $data['empty'] = false;
            } else {
                $data['empty'] = true;
                $this->emptyCart();
            }
        } catch (\Exception $exception) {
            log_exception($exception, \ShoppingCart::class, 'setQuantity');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        $message = array_merge($message, $data);
        return response()->json($message);
    }

    public function getCartItemsSummary()
    {
        $rendered_cart_summary = view('partials.cart_summary')->render();
        return response()->json(['cart_summary' => $rendered_cart_summary]);
    }


    public function emptyCart()
    {
        \ShoppingCart::destroyAllCartInstances();
        $message = ['level' => 'success', 'message' => trans(trans('Marketplace::labels.cart.cart_empty'))];
        return response()->json($message);
    }

    /**
     * @param AddToCartRequest $request
     * @param Product $product
     * @param SKU $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(AddToCartRequest $request, Product $product, SKU $sku)
    {
        $data = [];

        if (!$sku->exists) {
            $sku_hash = $request->get('sku_hash');
            $sku = SKU::findByHash($sku_hash);
        }

        $cart = \ShoppingCart::setInstance($sku->product->store->id);

        $quantity = $request->get('quantity', 1);

        $cart_quantity = 0;

        try {
            foreach ($cart->getItems() as $item) {
                if ($item->id->id == $sku->id) {
                    $cart_quantity += $item->qty;
                }
            }

            $sku->checkInventory(($cart_quantity + $quantity), true);

            $priceWithOffers = $sku->getPriceWithOffers($quantity, true);

            $price = data_get($priceWithOffers, 'price');

            $subtotal = data_get($priceWithOffers, 'subtotal');

            $quantity = data_get($priceWithOffers, 'quantity');

            $options = [
                'product_options' => $request->get('options', []),
                'subtotal' => $subtotal,
                'recalculateOnQty' => true
            ];

            if (Settings::get('marketplace_tax_tax_included_in_price')) {
                $options['calculatedIncludedTax'] = $sku->taxAmount;
            }

            $cart->add(
                $sku,
                null,
                $quantity,
                $price,
                $options
            );

            $message = [
                'level' => 'success',
                'message' => trans(trans('Marketplace::labels.cart.product_has_been_add')),
                'action_buttons' => [
                    trans('Marketplace::labels.checkout.cart_detail') => url('cart'),
                    //trans('Marketplace::labels.cart.proceed_checkout') => url('checkout')
                    trans('Marketplace::labels.cart.continue_shop') => url('shop')

                ]
            ];

            $data['total'] = \ShoppingCart::totalAllInstances();

            $data['cart_count'] = \ShoppingCart::countAllInstances();
        } catch (\Exception $exception) {
            report($exception);
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        $message = array_merge($message, $data);

        return response()->json($message);
    }

    public function loadAbandonedCart(Request $request, $email_token)
    {
        if (!user()) {
            $email = decrypt($email_token);

            $carts = UserCart::query()->where('email', $email)->get();

            \ShoppingCart::loadSavedCarts($carts, true);
        }

        return redirect('cart');
    }
}
