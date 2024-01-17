<?php

namespace Corals\Modules\Marketplace\Classes;

use Carbon\Carbon;
use Corals\Foundation\Facades\CoralsForm;
use Corals\Modules\Marketplace\DataTables\Scopes\SKUAttributesScope;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Jobs\HandleOrdersWithPayouts;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\AttributeOption;
use Corals\Modules\Marketplace\Models\AttributeSet;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Models\Category;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\ProductShipping;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Services\CheckoutService;
use Corals\Modules\Payment\Payment;
use Corals\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class Marketplace
{
    public $gateway;

    /**
     * Marketplace constructor.
     * @param null $gateway_key
     * @param array $params
     * @throws \Exception
     */
    function __construct($gateway_key = null, array $params = [])
    {
        if ($gateway_key) {
            $gateway = Payment::create($gateway_key);

            $config = config('payment_' . strtolower($gateway_key));

            if (!$config) {
                throw new \Exception(trans('Marketplace::exception.misc.invalid_gateway'));
            }

            $this->gateway = $gateway;

            $this->gateway->setAuthentication();

            foreach ($params as $key => $value) {
                $this->gateway->setParameter($key, $value);
            }
        }
    }

    /**
     * @param Product $product
     * @return bool
     * @throws \Exception
     */
    public function createProduct(Product $product)
    {
        $prod_integration_id = $this->gateway->getGatewayIntegrationId($product);
        if ($prod_integration_id) {
            $message = trans('Marketplace::exception.misc.product_code_exist', ['arg' => $prod_integration_id]);
            throw new \Exception($message);
        }
        $parameters = $this->gateway->prepareProductParameters($product);

        $request = $this->gateway->createProduct($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // Product was created successful
            $product->setGatewayStatus($this->gateway->getName(), 'CREATED', null, $data['id']);

            return true;
        } else {
            // Create Product failed
            $message = trans('Marketplace::exception.misc.create_gateway', ['message' => $response->getMessage()]);

            $product->setGatewayStatus($this->gateway->getName(), 'FAILED', $message, null);

            throw new \Exception($message);
        }
    }

    /**
     * @param Product $product
     * @return mixed
     * @throws \Exception
     */
    public function updateProduct(Product $product)
    {
        $parameters = $this->gateway->prepareProductParameters($product);
        $request = $this->gateway->updateProduct($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // Product was updated successful
            $product->setGatewayStatus($this->gateway->getName(), 'UPDATED', null, $data['id']);

            return true;
        } else {
            // update Product failed
            $message = trans('Marketplace::exception.misc.update_gateway', ['message' => $response->getMessage()]);

            $product->setGatewayStatus($this->gateway->getName(), 'FAILED', $message);

            throw new \Exception($message);
        }
    }

    /**
     * @param Product $product
     * @param array $extra_params
     * @return bool
     * @throws \Exception
     */
    public function deleteProduct(Product $product, $extra_params = [])
    {
        $parameters = ['id' => $product->code];

        $request = $this->gateway->deleteProduct($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            //"Gateway deleteProduct was successful.\n";
            return true;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.delete_product',
                ['message' => $response->getMessage()]));
        }
    }

    /**
     * @param SKU $sku
     * @return mixed
     * @throws \Exception
     */
    public function createSKU(SKU $sku)
    {
        $parameters = $this->gateway->prepareSKUParameters($sku);

        $request = $this->gateway->createSKU($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // SKU was created successful
            $sku->setGatewayStatus($this->gateway->getName(), 'CREATED', null, $data['id']);

            return true;
        } else {
            // Create SKU failed
            $message = trans('Marketplace::exception.misc.create_gateway_sku', ['message' => $response->getMessage()]);

            $sku->update(['gateway_status' => 'failed', 'gateway_message' => $message]);
            $sku->setGatewayStatus($this->gateway->getName(), 'FAILED', $message, null);

            throw new \Exception($message);
        }
    }

    /**
     * @param SKU $sku
     * @return bool
     * @throws \Exception
     */
    public function updateSKU(SKU $sku)
    {
        $parameters = $this->gateway->prepareSKUParameters($sku);
        $request = $this->gateway->updateSKU($parameters);

        $response = $request->send();
        if ($response->isSuccessful()) {
            $data = $response->getData();
            // SKU was updated successful
            $sku->setGatewayStatus($this->gateway->getName(), 'UPDATED', null, $data['id']);

            return true;
        } else {
            // update SKU failed
            $message = trans('Marketplace::exception.misc.update_gateway_sku', ['message' => $response->getMessage()]);

            $sku->setGatewayStatus($this->gateway->getName(), 'UPDATED', $message);

            throw new \Exception($message);
        }
    }

    /**
     * @param SKU $sku
     * @param array $extra_params
     * @return bool
     * @throws \Exception
     */
    public function deleteSKU(SKU $sku, $extra_params = [])
    {
        $parameters = ['id' => $sku->code];

        $request = $this->gateway->deleteSKU($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            //"Gateway deleteSKU was successful.\n";
            return true;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.delete_sku',
                ['message' => $response->getMessage()]));
        }
    }


    /**
     * @param User $user
     * @param $cartItems
     * @param $shipping_rate
     * @return mixed
     * @throws \Exception
     */
    public function createOrder(User $user, $cartItems, $shipping_rate)
    {
        $parameters = $this->gateway->prepareOrderParameters(null, $user, $cartItems, $shipping_rate);

        $request = $this->gateway->createOrder($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // Order was created successful

            $order_id = $data['id'];
            return $order_id;
        } else {
            // Create Order failed
            $message = trans('Marketplace::exception.misc.create_order_failed', ['message' => $response->getMessage()]);

            throw new \Exception($message);
        }
    }

    /**
     * @param Order $order
     * @param User $user
     * @param $cart_items
     * @return bool
     * @throws \Exception
     */
    public function updateOrder(Order $order, User $user, $cart_items)
    {
        $parameters = $this->gateway->prepareOrderParameters($order, $user, $cart_items);

        $request = $this->gateway->updateOrder($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            $order = Order::where('code', $data['id'])->first();

            if (!$order) {
                throw new \Exception(trans('Marketplace::exception.misc.invalid_order_code', ['data' => $data['id']]));
            }
            $order->items()->delete();

            $order->update([
                'amount' => ($data['amount'] / 100),
                'currency' => $data['currency'],
                'status' => $data['status'],
                'shipping_methods' => $data['shipping_methods'],

            ]);

            $this->createOrderItems($data, $order);


            return true;
        } else {
            // update Order failed
            $message = trans('Marketplace::exception.misc.update_gateway_order_failed',
                ['message' => $response->getMessage()]);

            throw new \Exception($message);
        }
    }

    /**
     * @param $data
     * @param $order
     */
    protected function createOrderItems($data, $order)
    {
        $items = [];

        foreach ($data['items'] as $item) {
            $items[] = [
                'amount' => ($item['amount'] / 100),
                'quantity' => $item['quantity'],
                'description' => $item['description'],
                'sku_code' => $item['parent'],
                'type' => $item['type'],
            ];
        }

        $order->items()->createMany($items);
    }


    /**
     * @param Order $order
     * @param User $user
     * @param $checkoutDetails
     * @return bool
     * @throws \Exception
     */
    public function payOrders($orders, User $user, $checkoutDetails)
    {
        foreach ($orders as $order) {
            if (isset($order->billing['payment_status']) && ($order->billing['payment_status'] == 'paid')) {
                throw new \Exception(trans('Marketplace::exception.misc.order_already_paid'));
            }
        }


        \Actions::do_action('pre_marketplace_pay_order', $this->gateway, $orders, $user, $checkoutDetails);

        $parameters = $this->gateway->prepareCreateMultiOrderChargeParameters($orders, $user, $checkoutDetails);

        $request = $this->gateway->createCharge($parameters);

        $response = $request->send();
        if ($response->isSuccessful()) {
            $payment_reference = $response->getChargeReference();
            \Actions::do_action('post_marketplace_pay_orders', $this->gateway, $orders, $user, $checkoutDetails);
            return $payment_reference;
        } else {
            // pay Order failed
            $message = 'pay Gateway Order Failed. ' . $response->getMessage();
            throw new \Exception($message);
        }
    }


    public function refundOrder(Order $order, $amount)
    {
        $parameters = $this->gateway->prepareCreateRefundParameters($order, $amount);

        $request = $this->gateway->refund($parameters);

        $response = $request->send();
        if ($response->isSuccessful()) {
            $refund_reference = $response->getTransactionReference();

            \Actions::do_action('post_marketplace_refund_order', $this->gateway, $order, $amount);

            return $refund_reference;
        } else {
            // refund Order failed
            $message = 'refund Gateway Order Failed. ' . $response->getMessage();
            throw new \Exception($message);
        }
    }


    public function setTransactions($invoice, $order)
    {
        $commission = 0;


        $order_amount_system_currency = \Payments::currency_convert($order->amount, $order->currency,
            \Payments::admin_currency_code(), false);;

        $commission_amount = \Store::getStoreCommissionAmount($order->store, $order_amount_system_currency);


        $transactions = [];
        if ($order->billing['payment_status'] == 'paid') {
            $transaction_status = 'completed';
        } else {
            $transaction_status = 'pending';
        }
        if ($commission_amount) {
            $iso_currencies = new ISOCurrencies();
            $currency = new Currency($order->currency);

            if ($currency) {
                $decimals = $iso_currencies->subunitFor($currency);
                $commission_amount = number_format((float)$commission_amount, $decimals, '.', '');
            }

            $transactions[] = [
                'invoice_id' => $invoice->id,
                'amount' => -1 * $commission_amount,
                'sourcable_id' => $order->id,
                'sourcable_type' => get_class($order),
                'transaction_date' => Carbon::now(),
                'status' => $transaction_status,
                'type' => 'commission',
                'notes' => 'Commission Fee for order# ' . $order->id,
            ];
        }

        $applied_shipping_item = $order->items()->where('type', 'Shipping')->first();

        if ($applied_shipping_item && $applied_shipping_item->amount > 0) {
            $shipping_rule = \Corals\Modules\Marketplace\Models\Shipping::find($applied_shipping_item->getProperty('shipping_rule_id'));

            if (!$shipping_rule) {
                $shipping_rule = ProductShipping::findOrFail($applied_shipping_item->getProperty('shipping_rule_id'));
            }

            if (!$shipping_rule->store_id) {
                $transactions[] = [
                    'invoice_id' => $invoice->id,
                    'amount' => -1 * $applied_shipping_item->amount,
                    'sourcable_id' => $order->id,
                    'sourcable_type' => get_class($order),
                    'transaction_date' => Carbon::now(),
                    'status' => $transaction_status,
                    'type' => 'shipping',
                    'notes' => 'Shipping Fee for order# ' . $order->id,
                ];
            }
        }

        $transactions[] = [
            'invoice_id' => $invoice->id,
            'paid_currency' => $order->currency,
            'paid_amount' => $order->amount,
            'reference' => $order->billing['payment_reference'],
            'amount' => $order_amount_system_currency,
            'sourcable_id' => $order->id,
            'status' => $transaction_status,
            'sourcable_type' => get_class($order),
            'transaction_date' => Carbon::now(),
            'type' => 'order_revenue',
            'notes' => 'Revenue from order# ' . $order->id,
        ];

        $order->store->user->transactions()->createMany($transactions);
    }

    /**
     * @return string
     */
    public function createOrderNumber()
    {
        // Get the last created order
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $number = 0;
        // We get here if there is no order at all
        // If there is no number set it to 0, which will be 1 at the end.
        if ($lastOrder) {
            $number = substr($lastOrder->order_number, 4);
        }

        // If we have ORD-000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %05d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return 'ORD-' . sprintf('%06d', intval($number) + 1);
    }

    public function getCategoriesRootList($objects = true)
    {
        $categories = Category::query();

        $categories = $categories->where(function ($parentQuery) {
            $parentQuery->whereNull('parent_id')
                ->orWhere('parent_id', 0);
        });

        if ($objects) {
            return $categories->get();
        } else {
            return $categories->pluck('name', 'id');
        }
    }

    /**
     * @param array $selected
     * @param array $excluded
     * @return array|mixed
     */
    public function getCategoriesList($selected = [], $excluded = [])
    {
        $categories = Category::query();

        $categoriesResult = [];

        $excluded = Arr::wrap($excluded);

        if (!empty($excluded)) {
            $categories->whereNotIn('id', $excluded);
        }

        $categories = $categories->where(function ($parentQuery) {
            $parentQuery->whereNull('parent_id')
                ->orWhere('parent_id', 0);
        })->get();

        foreach ($categories as $category) {
            $categoriesResult = $this->buildTree($categoriesResult, $category);
        }

        $selected = Arr::wrap($selected);

        foreach ($selected as $id) {
            $this->setTreeSelectedOptions($categoriesResult, $id);
        }

        return $categoriesResult;
    }

    /**
     * @param $options
     * @param $id
     */
    protected function setTreeSelectedOptions(&$options, $id)
    {
        foreach ($options as $index => $option) {
            if ($options[$index]['id'] == $id) {
                $options[$index]['selected'] = "true";
                return;
            }
            if (!empty($options[$index]['inc'])) {
                $this->setTreeSelectedOptions($options[$index]['inc'], $id);
            }
        }
    }

    /**
     * @param bool $pluck
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getAttributesList($pluck = true)
    {
        $attributes = Attribute::query();

        if (!Store::isStoreAdmin()) {
            $store = Store::getVendorStore();
            $attributes = $attributes
                ->where('store_id', $store->id)
                ->orWhereNull('store_id');
        }

        if ($pluck) {
            return $attributes->get()->pluck('label', 'id');
        } else {
            return $attributes->get();
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAttributeSetsList()
    {
        if (Store::isStoreAdmin()) {
            $attribute_sets = AttributeSet::all();
        } else {
            $store = Store::getVendorStore();
            $attribute_sets = AttributeSet::where('store_id', $store->id)->orWhereNull('store_id')->get();
        }
        return $attribute_sets->pluck('name', 'id');
    }

    public function getProductAttributeSets($product)
    {
        if ($product->exists) {
            return $product->attributeSets()->pluck('set_id')->toArray();
        } else {
            return $this->getDefaultAttributeSets()->pluck('id');
        }
    }

    public function getDefaultAttributeSets($query = true)
    {
        $attributeSets = AttributeSet::query()->isDefault();

        if ($query) {
            return $attributeSets;
        }

        return $attributeSets->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBrandsList()
    {
        if (Store::isStoreAdmin()) {
            $brands = Brand::all();
        } else {
            $store = Store::getVendorStore();
            $brands = Brand::where('store_id', $store->id)->orWhereNull('store_id')->get();
        }
        return $brands->pluck('name', 'id');
    }

    /**
     * @param $categories
     * @param $category
     * @param bool $isAChild
     * @return mixed
     */
    protected function appendCategory($categories, $category, $isAChild = false)
    {
        if ($category->hasChildren()) {
            $categories[$category->name] = [];
            foreach ($category->children as $child) {
                $categories[$category->name] = $this->appendCategory($categories[$category->name], $child, true);
            }
        } elseif ($isAChild || $category->isRoot()) {
            $categories[$category->id] = $category->name;
        }

        return $categories;
    }

    /**
     * @param $categories
     * @param $category
     * @param bool $isAChild
     * @return mixed
     */
    protected function buildTree($categories, $category)
    {
        $categories[$category->id] = ['id' => $category->id, 'text' => $category->name, 'inc' => []];

        if ($category->hasChildren()) {
            foreach ($category->children as $child) {
                $categories[$category->id]['inc'] = $this->buildTree($categories[$category->id]['inc'], $child);
            }
        }

        if (empty($categories[$category->id]['inc'])) {
            unset($categories[$category->id]['inc']);
        }

        return array_values($categories);
    }

    /**
     * @param bool $objects
     * @param null $status
     * @return mixed
     */
    public function getTagsList($objects = false, $status = null)
    {
        $tags = Tag::whereNotNull('id');

        if ($status) {
            $tags = $tags->where('status', $status);
        }

        if ($objects) {
            $tags = $tags->get();
        } else {
            $tags = $tags->pluck('name', 'id');
        }

        return $tags;
    }

    /**
     * @param Order $order
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function createPaymentToken($amount, $currency, $params)
    {
        $description = "Payment for Vendors";

        $parameters = $this->gateway->preparePaymentTokenParameters($amount, $currency, $description, $params);
        $request = $this->gateway->purchase($parameters);
        $response = $request->send();


        if ($response->isSuccessful()) {
            $token = $response->getPaymentTokenReference();
            return $token;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.gateway_create_payment',
                ['data' => $response->getDataText()]));
        }
    }


    /**
     * @param Order $order
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function checkPaymentToken($params)
    {
        $parameters = $this->gateway->prepareCheckPaymentTokenParameters($params);

        if ($this->gateway->getConfig('require_token_confirm')) {
            $request = $this->gateway->confirmPaymentToken($parameters);
        } else {
            $request = $this->gateway->checkPaymentToken($parameters);
        }

        $response = $request->send();

        if ($response->isSuccessful()) {
            $token = $response->getPaymentTokenReference();
            return $token;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.gateway_check_payment_token',
                ['data' => $response->getDataText()]));
        }
    }


    /**
     * @param Order $order
     */
    public function deductFromInventory(Order $order)
    {
        try {
            foreach ($order->items as $order_item) {
                if ($order_item->type == "Product") {
                    $sku = SKU::where('code', $order_item->sku_code)->first();
                    if ($sku && $sku->inventory == "finite") {
                        $sku->inventory_value = $sku->inventory_value - 1;
                        $sku->save();
                    }
                }
            }
        } catch (\Exception $exception) {
            log_exception($exception, 'Inventory', 'Deduct');
        }
    }

    /**
     * @param Order $order
     */
    public function increaseTotalSales(Order $order)
    {
        try {
            foreach ($order->items as $order_item) {
                if ($order_item->type == "Product") {
                    $sku = SKU::where('code', $order_item->sku_code)->first();
                    if ($sku && $sku->product) {
                        $sku->product->increment('total_sales');
                    }
                }
            }
        } catch (\Exception $exception) {
            log_exception($exception, self::class, 'increaseTotalSales');
        }
    }

    /**
     * @param Order $order
     * @param User $user
     */
    public function addContentAccess(Order $order, User $user)
    {
        try {
            $posts = [];
            foreach ($order->items as $order_item) {
                if ($order_item->type == "Product") {
                    $sku = SKU::where('code', $order_item->sku_code)->first();
                    if ($sku) {
                        $product_posts = $sku->product->posts;
                        if (count($product_posts)) {
                            foreach ($product_posts as $product_post) {
                                $posts[] = [
                                    'content_id' => $product_post->id,
                                    'postable_id' => $user->id,
                                    'postable_type' => User::class,
                                    'sourcable_id' => $order->id,
                                    'sourcable_type' => Order::class
                                ];
                            }
                        }
                    }
                }
            }
            $user->posts()->sync($posts, false);
        } catch (\Exception $exception) {
            log_exception($exception, 'Inventory', 'Deduct');
        }
    }

    /**
     * @param $field
     * @param null $sku
     * @param array $attributes
     * @return string
     */
    public function renderAttribute($field, $sku = null, $attributes = [])
    {
        $value = null;

        $asFilter = \Arr::pull($attributes, 'as_filter', false);

        $fieldName = Arr::pull($attributes, 'field_name', 'options');

        $fieldName = "{$fieldName}[{$field->id}]";

        if ($sku) {
            $options = $sku->options()->where('attribute_id', $field->id)->get();
            if ($options->count() > 1) {
                // in case of multiple type
                $value = AttributeOption::whereIn('id', $options->pluck('number_value')->toArray())
                    ->pluck('id')->toArray();
            } elseif ($option = $options->first()) {
                $value = optional($option)->value;
            }
        }

        $input = '';

        switch ($field->type) {
            case 'label':
                unset($attributes['class']);
                $input = CoralsForm::{$field->type}($fieldName, $field->label, $attributes);
                break;
            case 'number':
            case 'date':
            case 'text':
            case 'color':
            case 'textarea':
                if ($field->type == 'number') {
                    $attributes = array_merge(['step' => '0.01'], $attributes);
                }
                $input = CoralsForm::{$field->type}($fieldName, $field->label,
                    $asFilter ? false : $field->required, $value ?? '', $attributes);
                break;
            case 'checkbox':
                $input = CoralsForm::{$field->type}($fieldName, $field->label, $value, 1,
                    $attributes);
                break;
            case 'checkboxes':
                switch ($field->getProperty('display_type')) {
                    case 'color':
                        $options = $this->renderSpecialOptions($field, 'color');
                        break;
                    case 'image':
                        $options = $this->renderSpecialOptions($field, 'images');
                        break;
                    default:
                        $options = $field->options->pluck('option_display', 'id')->toArray();
                }

                $input = CoralsForm::{$field->type}($fieldName . '[]', $field->label,
                    $asFilter ? false : $field->required, $options,
                    $value, $attributes);
                break;
            case 'radio':
                $input = CoralsForm::{$field->type}($fieldName, $field->label,
                    $asFilter ? false : $field->required, $field->options->pluck('option_display', 'id')->toArray(),
                    $value, $attributes);
                break;
            case 'select':

                switch ($field->getProperty('display_type')) {
                    case 'color':
                        $options = $this->renderSpecialOptions($field, 'color');

                        $input = CoralsForm::radio($fieldName, $field->label,
                            $asFilter ? false : $field->required,
                            $options,
                            $value, $attributes);
                        break;

                    case 'image':
                        $options = $this->renderSpecialOptions($field, 'images');

                        $input = CoralsForm::radio($fieldName, $field->label,
                            $asFilter ? false : $field->required,
                            $options ?? [],
                            $value, $attributes);

                        break;
                    default:
                        $input = CoralsForm::{$field->type}($fieldName, $field->label,
                            $field->options->pluck('option_display', 'id')->toArray(),
                            $asFilter ? false : $field->required,
                            $value, $attributes, 'select2');
                        break;
                }

                break;
            case 'tag':
                //this is special for bulk generate sku
                $attributes['class'] = 'select2-normal tags';
                $attributes['multiple'] = true;
                $input = CoralsForm::select($fieldName . '[]', $field->label,
                    [], $asFilter ? false : $field->required, [], $attributes, 'select2');
                break;
            case 'multi_values':
                switch ($field->getProperty('display_type')) {
                    case 'color':
                        $options = $options = $this->renderSpecialOptions($field, 'color');

                        $input = CoralsForm::checkboxes($fieldName . '[]', $field->label,
                            $asFilter ? false : $field->required, $options,
                            $value, $attributes);
                        break;
                    case 'image':
                        $options = $this->renderSpecialOptions($field, 'images');
                        $input = CoralsForm::checkboxes($fieldName . '[]', $field->label,
                            $asFilter ? false : $field->required, $options,
                            $value, $attributes);
                        break;
                    default:
                        $attributes = array_merge(['class' => 'select2-normal', 'multiple' => true], $attributes);

                        $options = $field->options->pluck('option_display', 'id')->toArray();

                        $input = CoralsForm::select($fieldName . '[]', $field->label,
                            $options, $asFilter ? false : $field->required, $value, $attributes, 'select2');
                        break;
                }
                break;
        }

        return $input;
    }

    public function redirectHandler($objectData, $payment_reference, $payment_status)
    {
        $user = user() ?? new User();

        $cart_instances = \ShoppingCart::getInstances();

        $orders = [];

        foreach ($cart_instances as $instance) {
            $cart = \ShoppingCart::setInstance($instance);
            $cart_items_count = $cart->count();

            if ($cart_items_count > 0) {
                if ($cart->getAttribute('order_id')) {
                    $order = Order::find($cart->getAttribute('order_id'));

                    if ($order) {
                        $orders[] = $order;
                    }
                }
            }
        }

        switch ($payment_status) {
            case 'paid':
                $order_status = 'processing';

                $this->checkoutOrder($orders, $objectData['gateway'],
                    $payment_reference, $payment_status, $order_status, $user);
                break;
            case 'canceled':
                $order_status = 'failed';

                foreach ($orders as $order) {
                    $billing = $order->billing;

                    $billing['payment_status'] = $payment_status;

                    $order->update([
//                        'status' => $order_status,
                        'billing' => $billing,
                    ]);
                }

//                \ShoppingCart::destroyAllCartInstances();

                session()->flash('failed-message', data_get($objectData, 'message'));

                return redirect('checkout/order-failed');
        }

        return redirectTo('checkout/order-success');
    }

    public function checkoutOrder($orders, $gateway_name, $payment_reference, $payment_status, $order_status, $user)
    {
        $shipping_address = \ShoppingCart::get('default')->getAttribute('shipping_address');
        $billing_address = \ShoppingCart::get('default')->getAttribute('billing_address');

        $checkoutService = new CheckoutService();

        foreach ($orders as $order) {
            $billing = $order->billing;
            $billing['payment_reference'] = $payment_reference;
            $billing['gateway'] = $gateway_name;
            $billing['payment_status'] = $payment_status;

            $order->update([
                'status' => $order_status,
                'billing' => $billing,
            ]);

            $invoice = $checkoutService->generateOrderInvoice($order, $payment_status, $user, $billing_address);

            $checkoutService->setOrderShippingDetails($order, $shipping_address);

            $checkoutService->orderFulfillment($order, $invoice, $user);

            \ShoppingCart::destroyAllCartInstances();
        }

        if (\Settings::get('marketplace_payout_payout_mode') == "immediate") {
            foreach ($orders as $order) {
                dispatch(new HandleOrdersWithPayouts($order));
            }
        }
    }

    /**
     * @param $field
     * @param $type
     * @param null $options
     * @return array
     */
    protected function renderSpecialOptions($field, $type, $options = null)
    {
        if (!$options) {
            $options = $field->options;
        }


        switch ($type) {
            case 'color':
                return $options->mapWithKeys(function ($option) {
                    return [
                        $option->id =>
                            "<div title='{$option->option_value}' style=\"display:inline-block;background-color:{$option->option_display};height: 100%;width: 25px;\">&nbsp;</div>"
                    ];
                })->toArray();

            case 'images':
                $optionsValues = [];

                foreach ($options as $option) {
                    $optionsValues[$option->id] = sprintf("<img src='%s' title='{$option->option_value}' style='max-width: 100px;max-height: 100px' alt='img'>",
                        $option->media()->first()->getFullUrl());
                }
                return $optionsValues ?? [];
        }
    }

    /**
     * @return array
     */
    public function renderSKUAttributesForFilters(): array
    {
        $attributes = Attribute::query()
            ->join('marketplace_product_attributes', function ($joinProductAttributes) {
                $joinProductAttributes->on('marketplace_attributes.id', 'marketplace_product_attributes.attribute_id')
                    ->where('marketplace_product_attributes.product_id', request('product')->id);
            })->join('marketplace_sku_options', 'marketplace_attributes.id', 'marketplace_sku_options.attribute_id')
            ->select('marketplace_attributes.*')
            ->distinct('marketplace_attributes.id')
            ->get();

        $filters = [];

        foreach ($attributes as $attribute) {
            switch ($attribute->type) {
                case 'select':
                case 'radio':
                case 'multi_values':
                    $attribute->type = 'checkboxes';
                    break;
                case 'number':
                case 'date':
                case 'text':
                case 'textarea':
                    $attribute->type = 'multi_values';
                    break;
            }
            $html = $this->renderAttributeForFilter($attribute, ['class' => 'filter']);

            if ($html) {
                $filters[$attribute->code] = [
                    'html' => $html,
                    'active' => true,
                    'class' => 'col-md-3',
                    'builder' => SKUAttributesScope::class
                ];
            }
        }

        return $filters;
    }

    protected function renderAttributeForFilter($field, $attributes = [])
    {
        $value = null;

        $input = '';

        switch ($field->type) {
            case 'number':
            case 'date':
            case 'text':
            case 'color':
            case 'textarea':
                $input = CoralsForm::{$field->type}($field->code, $field->label,
                    false, $value ?? '', $attributes);
                break;
            case 'checkbox':
                $input = CoralsForm::{$field->type}($field->code, $field->label, $value, 1,
                    $attributes);
                break;
            case 'checkboxes':
                $options = $field->options()
                    ->join('marketplace_sku_options', function ($joinSKUOption) use ($field) {
                        $type = $field->getRawOriginal('type');

                        switch ($type) {
                            case 'checkbox':
                            case 'text':
                            case 'date':
                                $name = 'string_value';
                                break;
                            case 'textarea':
                                $name = 'text_value';
                                break;
                            case 'number':
                            case 'select':
                            case 'multi_values':
                            case 'radio':
                                $name = 'number_value';
                                break;
                            default:
                                $name = 'string_value';
                        }

                        $joinSKUOption->on('marketplace_attribute_options.id', "marketplace_sku_options.$name")
                            ->where('marketplace_sku_options.attribute_id', $field->id);
                    })->join('marketplace_sku', 'marketplace_sku_options.sku_id', 'marketplace_sku.id')
                    ->where('marketplace_sku.product_id', request('product')->id)
                    ->select('marketplace_attribute_options.*')
                    ->get();

                if ($options->isEmpty()) {
                    return null;
                }

                switch ($field->getProperty('display_type')) {
                    case 'color':
                        $options = $this->renderSpecialOptions($field, 'color', $options);
                        break;
                    case 'image':
                        $options = $this->renderSpecialOptions($field, 'images', $options);
                        break;
                    default:
                        $options = $options->pluck('option_display', 'id')->toArray();
                }

                $attributes['help_text'] = $field->label;

                $input = CoralsForm::{$field->type}($field->code . "[]", '',
                    false, $options,
                    $value, $attributes);
                break;
            case 'radio':
                $options = $field->options->pluck('option_display', 'id')->toArray();

                if (empty($options)) {
                    return null;
                }

                $input = CoralsForm::{$field->type}($field->code, $field->label,
                    false, $options,
                    $value, $attributes);
                break;
            case 'select':

                switch ($field->getProperty('display_type')) {
                    case 'color':
                        $options = $this->renderSpecialOptions($field, 'color');

                        if (empty($options)) {
                            return null;
                        }

                        $input = CoralsForm::radio($field->code, $field->label,
                            false,
                            $options,
                            $value, $attributes);
                        break;

                    case 'image':
                        $options = $this->renderSpecialOptions($field, 'images');

                        if (empty($options)) {
                            return null;
                        }

                        $input = CoralsForm::radio($field->code, $field->label,
                            false,
                            $options ?? [],
                            $value, $attributes);

                        break;
                    default:
                        $options = $field->options->pluck('option_display', 'id')->toArray();

                        if (empty($options)) {
                            return null;
                        }

                        $input = CoralsForm::{$field->type}($field->code, $field->label,
                            $options, false,
                            $value, $attributes, 'select2');
                        break;
                }
                break;
            case 'tag':
                //this is special for bulk generate sku
                $attributes['class'] = 'select2-normal tags filter';
                $attributes['multiple'] = true;
                $input = CoralsForm::select($field->code . '[]', $field->label,
                    [], false, [], $attributes, 'select2');
                break;
            case 'multi_values':

                $options = DB::select("select distinct CASE
                          WHEN marketplace_attributes.type  in ('checkbox', 'text','date') THEN marketplace_sku_options.string_value
                          WHEN marketplace_attributes.type in ('textarea') THEN marketplace_sku_options.text_value
                          WHEN marketplace_attributes.type in ('number','select','multi_values','radio') THEN marketplace_sku_options.number_value
                    ELSE
						        marketplace_sku_options.string_value
                    END as value from `marketplace_sku_options` 
                    inner join `marketplace_attributes` on `marketplace_sku_options`.`attribute_id` = `marketplace_attributes`.`id`
                    inner join marketplace_sku on marketplace_sku_options.sku_id = marketplace_sku.id  
						   where `attribute_id` = ? and marketplace_sku.product_id = ?",
                    [$field->id, request('product')->id]);

                $options = collect($options)->mapWithKeys(function ($option) {
                    return [data_get($option, 'value') => data_get($option, 'value')];
                })->toArray();

                if (empty($options)) {
                    return null;
                }

                $attributes = array_merge([
                    'class' => 'select2-normal filter',
                    'multiple' => true,
                    'placeholder' => "Select $field->code"
                ], $attributes);

                $input = CoralsForm::select($field->code . '[]', '',
                    $options, false,
                    $value, $attributes, 'select2');
                break;
        }

        return $input;
    }

    /**
     * @param Product $product
     * @return string
     */
    public function bulkPriceType(Product $product): string
    {
        if ($product->offers) {
            $type = 'offers';
        }

        if ($product->price_per_quantity) {
            $type = 'price_per_quantity';
        }

        return $type ?? 'none';
    }

}
