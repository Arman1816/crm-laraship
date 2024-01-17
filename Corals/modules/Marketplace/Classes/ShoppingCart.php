<?php

namespace Corals\Modules\Marketplace\Classes;

use Corals\Modules\Marketplace\Contracts\CouponContract;
use Corals\Modules\Marketplace\Contracts\ShoppingCartContract;
use Corals\Modules\Marketplace\Exceptions\ModelNotFound;
use Corals\Modules\Marketplace\Models\UserCart;
use Corals\Settings\Facades\Settings;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Session\SessionManager;

/**
 * Class ShoppingCart.
 */
class ShoppingCart implements ShoppingCartContract
{
    const SERVICE = 'shoppingcart';


    protected $events;
    protected $session;
    protected $authManager;

    public $cart;
    public $prefix;
    public $itemModel;
    public $itemModelRelations;

    /**
     * ShoppingCart constructor.
     *
     * @param SessionManager $session
     * @param Dispatcher $events
     * @param AuthManager $authManager
     */
    public function __construct(SessionManager $session, Dispatcher $events, AuthManager $authManager)
    {
        $this->session = $session;
        $this->events = $events;
        $this->authManager = $authManager->guard(config('shoppingcart.guard', null));
        $this->prefix = config('shoppingcart.cache_prefix', 'shoppingcart');
        $this->itemModel = config('shoppingcart.item_model', null);
        $this->itemModelRelations = config('shoppingcart.item_model_relations', []);

        if ($this->isSaveCartEnabled() && user()) {
            $user_carts = user()->carts()->get();

            $this->loadSavedCarts($user_carts);
        }

        $this->setInstance($this->session->get($this->prefix . '.instance', 'default'));
    }

    /**
     * @param $carts
     * @param bool $forceLoad
     */
    public function loadSavedCarts($carts, bool $forceLoad = false)
    {
        if (!$this->session->has($this->prefix . '.db_loaded') || $forceLoad) {
            if ($forceLoad) {
                $this->destroyAllCartInstances();
            }

            foreach ($carts as $saved_cart) {
                $cart = unserialize($saved_cart->cart);

                if (isset($cart->items) && is_array($cart->items) && (count($cart->items) > 0)
                    || $saved_cart->instance_id == 'default') {
                    $this->session->put($this->prefix . '.' . $saved_cart->instance_id, $cart);

                    if (!in_array($saved_cart->instance_id, $this->getInstances())) {
                        $this->session->push($this->prefix . '.instances', $saved_cart->instance_id);
                    }
                } else {
                    $saved_cart->delete();
                }
            }

            $this->session->reflash();

            $this->session->save();

            $this->session->put($this->prefix . '.db_loaded', true);
        }
    }

    /**
     * @return bool
     */
    protected function isSaveCartEnabled(): bool
    {
        return config('shoppingcart.save_database', false);
    }

    /**
     * Gets all current instances inside the session.
     *
     * @return mixed
     */
    public function getInstances()
    {
        return $this->session->get($this->prefix . '.instances', []);
    }

    /**
     * Sets and Gets the instance of the cart in the session we should be using.
     *
     * @param string $instance
     *
     * @return ShoppingCart
     */
    public function setInstance($instance = 'default')
    {
        $this->get($instance);

        $this->session->put($this->prefix . '.instance', $instance);

        if (!in_array($instance, $this->getInstances())) {
            $this->session->push($this->prefix . '.instances', $instance);
        }
        $this->events->dispatch('shoppingcart.new');

        return $this;
    }

    /**
     * Gets the instance in the session.
     *
     * @param string $instance
     *
     * @return $this cart instance
     */
    public function get($instance = 'default')
    {
        if (empty($this->cart = $this->session->get($this->prefix . '.' . $instance))) {
            $this->cart = new Cart($instance);
        }

        return $this;
    }

    /**
     * Gets an an attribute from the cart.
     *
     * @param $attribute
     * @param $defaultValue
     *
     * @return mixed
     */
    public function getAttribute($attribute, $defaultValue = null)
    {
        return \Arr::get($this->cart->attributes, $attribute, $defaultValue);
    }

    /**
     * Gets all the carts attributes.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->cart->attributes;
    }

    /**
     * Adds an Attribute to the cart.
     *
     * @param $attribute
     * @param $value
     */
    public function setAttribute($attribute, $value)
    {
        \Arr::set($this->cart->attributes, $attribute, $value);

        $this->update();
    }

    /**
     * Updates cart session.
     */
    public function update()
    {
        $this->session->put($this->prefix . '.' . $this->cart->instance, $this->cart);

        if ($this->isSaveCartEnabled()) {
            $cart_instances = \ShoppingCart::getInstances();

            $billingAddress = \ShoppingCart::get('default')->getAttribute('billing_address') ?? [];

            $email = $billingAddress['email'] ?? optional(user())->email;

            foreach ($cart_instances as $instance) {
                $shoppingCart = \ShoppingCart::setInstance($instance);

                if ($email) {
                    $userCart = UserCart::query()->firstOrNew(['email' => $email, 'instance_id' => $instance], [
                        'user_id' => optional(user())->id,
                        'email' => $email,
                        'instance_id' => $instance
                    ]);

                    $userCart->fill([
                        'cart' => serialize($shoppingCart->cart)
                    ]);

                    $userCart->save();
                }
            }
        }

        $this->session->reflash();

        $this->session->save();

        $this->events->dispatch('shoppingcart.update', $this->cart);
    }

    /**
     * Removes an attribute from the cart.
     *
     * @param $attribute
     */
    public function removeAttribute($attribute)
    {
        \Arr::forget($this->cart->attributes, $attribute);

        $this->update();
    }

    /**
     * Creates a CartItem and then adds it to cart.
     * @param int|string $itemID
     * @param null $name
     * @param int $qty
     * @param string $price
     * @param array $options
     * @param bool $taxable
     * @return CartItem
     * @throws ModelNotFound
     */
    public function addLine($itemID, $name = null, $qty = 1, $price = '0.00', $options = [], $taxable = true)
    {
        return $this->add($itemID, $name, $qty, $price, $options, $taxable, true);
    }

    /**
     * Creates a CartItem and then adds it to cart.
     *
     * @param $itemID
     * @param null $name
     * @param int $qty
     * @param string $price
     * @param array $options
     * @param bool|false $taxable
     * @param bool|false $lineItem
     *
     * @return CartItem
     * @throws ModelNotFound
     *
     */
    public function add(
        $itemID,
        $name = null,
        $qty = 1,
        $price = '0.00',
        $options = [],
        $taxable = true,
        $lineItem = false
    ) {
        if (!empty(config('shoppingcart.item_model'))) {
            $itemModel = $itemID;

            if (!$this->isItemModel($itemModel)) {
                $itemModel = (new $this->itemModel())->with($this->itemModelRelations)->find($itemID);
            }

            if (empty($itemModel)) {
                throw new ModelNotFound('Could not find the item ' . $itemID);
            }

            $bindings = config('shoppingcart.item_model_bindings');

            $itemID = $itemModel[$bindings[\Corals\Modules\Marketplace\Classes\CartItem::ITEM_ID]];

            if (is_int($name)) {
                $qty = $name;
            }

            $name = $itemModel[$bindings[\Corals\Modules\Marketplace\Classes\CartItem::ITEM_NAME]];
            $price = $itemModel[$bindings[\Corals\Modules\Marketplace\Classes\CartItem::ITEM_PRICE]];

            //$options['model'] = $itemModel;

            // $options = array_merge($options,
            //    $this->getItemModelOptions($itemModel, $bindings[\Corals\Modules\Marketplace\Classes\CartItem::ITEM_OPTIONS]));

            $taxable = $itemModel[$bindings[\Corals\Modules\Marketplace\Classes\CartItem::ITEM_TAXABLE]] ? true : false;
        }

        $item = $this->addItem(new CartItem(
            $itemID,
            $name,
            $qty,
            $price,
            $options,
            $taxable,
            $lineItem
        ));

        $this->update();

        return $this->getItem($item->getHash());
    }

    /**
     * Adds the cartItem into the cart session.
     *
     * @param CartItem $cartItem
     *
     * @return CartItem
     */
    public function addItem(CartItem $cartItem)
    {
        $itemHash = $cartItem->generateHash();

        if ($this->getItem($itemHash)) {
            $existing = $this->getItem($itemHash);
            $qty = $existing->qty + $cartItem->qty;

            if ($cartItem->recalculateOnQty) {
                $sku = $existing->id;

                $priceWithOffers = $sku->getPriceWithOffers($qty, true);

                $price = data_get($priceWithOffers, 'price');

                $subtotal = data_get($priceWithOffers, 'subtotal');

                $qty = data_get($priceWithOffers, 'quantity');

                $existing->price = $price;
                $existing->subtotal = $subtotal;
            }

            $existing->qty = $qty;
        } else {
            $this->cart->items[] = $cartItem;
        }

        $this->events->dispatch('shoppingcart.addItem', $cartItem);
        $this->update();
        return $cartItem;
    }

    /**
     * Increment the quantity of a cartItem based on the itemHash.
     *
     * @param $itemHash
     *
     * @return CartItem | null
     */
    public function increment($itemHash)
    {
        $item = $this->getItem($itemHash);
        $item->qty++;
        $this->update();

        return $item;
    }

    /**
     * Decrement the quantity of a cartItem based on the itemHash.
     *
     * @param $itemHash
     *
     * @return CartItem | null
     */
    public function decrement($itemHash)
    {
        $item = $this->getItem($itemHash);
        if ($item->qty > 1) {
            $item->qty--;
            $this->update();

            return $item;
        }
        $this->removeItem($itemHash);
        $this->update();
    }

    /**
     * Find items in the cart matching a data set.
     *
     * param $data
     *
     * @return array | CartItem | null
     */
    public function find($data)
    {
        $instances = $this->getInstances();
        $matches = [];

        foreach ($instances as $instance) {
            $this->setInstance($instance);
            foreach ($this->getItems() as $item) {
                if ($item->find($data)) {
                    $matches[] = $item;
                }
            }
        }


        switch (count($matches)) {
            case 0:
                return;
                break;
            case 1:
                return $matches[0];
                break;
            default:
                return $matches;
        }
    }

    /**
     * Finds a cartItem based on the itemHash.
     *
     * @param $itemHash
     *
     * @return CartItem | null
     */
    public function getItem($itemHash)
    {
        return \Arr::get($this->getItems(), $itemHash);
    }

    /**
     * Gets all the items within the cart instance.
     *
     * @return array
     */
    public function getItems()
    {
        $items = [];
        if (isset($this->cart->items) === true) {
            foreach ($this->cart->items as $item) {
                $items[$item->getHash()] = $item;
            }
        }

        return $items;
    }

    /**
     * Gets all the items within the cart instance.
     *
     * @return array
     */
    public function getAllInstanceItems()
    {
        $items = [];

        $instances = $this->getInstances();

        foreach ($instances as $instance) {
            $this->setInstance($instance);
            $items = array_merge($items, $this->getItems());
        }

        return $items;
    }

    /**
     * @param $itemHash
     * @param $key
     * @param $value
     * @return CartItem|void|null
     */
    public function updateItem($itemHash, $key, $value)
    {
        if (empty($item = $this->getItem($itemHash)) === false) {
            if ($key == 'qty' && $value == 0) {
                $this->removeItem($itemHash);
            }

            $item->$key = $value;
        }

        $this->update();

        return $item;
    }

    /**
     * Removes a CartItem based on the itemHash.
     *
     * @param $itemHash
     */
    public function removeItem($itemHash)
    {
        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            $this->setInstance($instance);
            $found = false;
            if (empty($this->cart->items) === false) {
                foreach ($this->cart->items as $itemKey => $item) {
                    if ($item->getHash() == $itemHash) {
                        unset($this->cart->items[$itemKey]);
                        $found = true;
                        break;
                    }
                }

                $this->events->dispatch('shoppingcart.removeItem', $item);

                $this->update();
            }
            if ($found) {
                break;
            }
        }
    }

    /**
     * Empties the carts items.
     */
    public function emptyCart()
    {
        unset($this->cart->items);

        $this->update();

        $this->events->dispatch('shoppingcart.empty', $this->cart->instance);
    }

    /**
     * Completely destroys cart instance and anything associated with it.
     */
    public function destroyCart()
    {
        $instance = $this->cart->instance;

        $this->session->forget($this->prefix . '.' . $instance);

        $this->events->dispatch('shoppingcart.destroy', $instance);

        $this->cart = new Cart($instance);

        $this->update();
    }

    /**
     * Completely destroys cart instance and anything associated with it.
     */
    public function destroyAllCartInstances()
    {
        $instances = $this->getInstances();

        foreach ($instances as $instance) {
            $shoppingCart = $this->setInstance($instance);

            if ($instance == 'default') {
                $billingAddress = $shoppingCart->getAttribute('billing_address') ?? [];

                $email = $billingAddress['email'] ?? optional(user())->email;

                UserCart::query()->where('email', $email)->delete();
            }

            $this->destroyCart();
        }
    }

    /**
     * Gets the coupons for the current cart.
     *
     * @return array
     */
    public function getCoupons()
    {
        return $this->cart->coupons;
    }

    /**
     * Finds a specific coupon in the cart.
     *
     * @param $code
     *
     * @return mixed
     */
    public function findCoupon($code)
    {
        return \Arr::get($this->cart->coupons, $code);
    }

    /**
     * Applies a coupon to the cart.
     *
     * @param CouponContract $coupon
     */
    public function addCoupon(CouponContract $coupon)
    {
        if (!$this->cart->multipleCoupons) {
            $this->cart->coupons = [];
        }

        $this->cart->coupons[$coupon->code] = $coupon;

        $this->update();
    }

    /**
     * Removes a coupon in the cart.
     *
     * @param $code
     */
    public function removeCoupon($code)
    {
        $this->removeCouponFromItems($code);
        \Arr::forget($this->cart->coupons, $code);
        $this->update();
    }

    /**
     * Removes all coupons from the cart.
     */
    public function removeCoupons()
    {
        $this->removeCouponFromItems();
        $this->cart->coupons = [];
        $this->update();
    }

    /**
     * Gets a specific fee from the fees array.
     *
     * @param $name
     *
     * @return mixed
     */
    public function getFee($name)
    {
        return \Arr::get($this->cart->fees, $name, new CartFee(null, false));
    }

    /**
     * Allows to charge for additional fees that may or may not be taxable
     * ex - service fee , delivery fee, tips.
     *
     * @param $name
     * @param $amount
     * @param bool|false $taxable
     * @param array $options
     */
    public function addFee($name, $amount, $taxable = false, array $options = [])
    {
        \Arr::set($this->cart->fees, $name, new CartFee($amount, $taxable, $options));

        $this->update();
    }

    /**
     * Removes a fee from the fee array.
     *
     * @param $name
     */
    public function removeFee($name)
    {
        \Arr::forget($this->cart->fees, $name);

        $this->update();
    }

    /**
     * Removes all the fees set in the cart.
     */
    public function removeFees()
    {
        $this->cart->fees = [];

        $this->update();
    }

    /**
     * Gets the total tax for the cart.
     *
     * @param bool|true $format
     * @param bool|true $withFees
     *
     * @return string
     */
    public function taxTotal($format = true, $withFees = true)
    {
        $totalTax = 0;
        $discounted = 0;
        $totalDiscount = $this->totalDiscount(false, false);

        if ($this->count() != 0) {
            /**
             * @var
             * @var CartItem $item
             */
            foreach ($this->getItems() as $index => $item) {
                if ($discounted >= $totalDiscount) {
                    $totalTax += $item->tax();
                } else {
                    $itemPrice = $item->subTotal(false, config('shoppingcart.discountTaxable', false));
                    if (($discounted + $itemPrice) > $totalDiscount) {
                        $totalTax += config('shoppingcart.discountTaxable',
                            false) ? $item->tax() : $item->tax($totalDiscount - $discounted);
                    }

                    $discounted += $itemPrice;
                }
            }
        }

        if ($withFees) {
            foreach ($this->getFees() as $fee) {
                if ($fee->taxable) {
                    $totalTax += $fee->amount * $fee->tax;
                } elseif ($fee->calculatedIncludedTax) {
                    $totalTax += $fee->calculatedIncludedTax;
                }
            }
        }

        return $this->formatMoney($totalTax, null, null, $format);
    }


    /**
     * Gets the total tax for the cart.
     *
     * @param bool|true $format
     * @param bool|true $withFees
     *
     * @return string
     */
    public function taxTotalAllInstances($format = true, $withFees = true)
    {
        $totalTax = 0;
        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            $this->setInstance($instance);
            $totalTax += $this->taxTotal(false, $withFees);
        }


        return $this->formatMoney($totalTax, null, null, $format);
    }


    /**
     * Gets the total of the cart with or without tax.
     *
     * @param bool $format
     * @param bool $withDiscount
     * @param bool $withTax
     * @param bool $withFees
     *
     * @return string
     */
    public function total($format = true, $withDiscount = true, $withTax = true, $withFees = true)
    {
        $total = $this->subTotal(false);

        if ($withFees) {
            $total += $this->feeTotals(false);
        }

        if ($withDiscount) {
            $total -= $this->totalDiscount(false, false);
        }

        if ($withTax && !Settings::get('marketplace_tax_tax_included_in_price')) {
            $total += $this->taxTotal(false, $withFees);
        }

        return $this->formatMoney($total, null, null, $format);
    }


    /**
     * Gets the total of the cart with or without tax.
     *
     * @param bool $format
     * @param bool $withDiscount
     * @param bool $withTax
     * @param bool $withFees
     *
     * @return string
     */
    public function totalAllInstances($format = true, $withDiscount = true, $withTax = true, $withFees = true)
    {
        $total = 0;

        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            $this->setInstance($instance);
            $total += $this->total(false, $withDiscount,
                $withTax, $withFees);
        }

        return $this->formatMoney($total, null, null, $format);
    }

    /**
     * Gets the subtotal of the cart with or without tax.
     *
     * @param bool $format
     * @param bool $withDiscount
     * @param bool $withoutTaxIncluded
     *
     * @return string
     */
    public function subTotal($format = true, $withDiscount = true, $withoutTaxIncluded = false)
    {
        $total = 0;


        if ($this->count() != 0) {
            foreach ($this->getItems() as $item) {
                $total += $item->subTotal(false, $withDiscount);
            }

            if (Settings::get('marketplace_tax_tax_included_in_price') && $withoutTaxIncluded) {
                foreach ($this->getItems() as $item) {
                    $total -= $item->getCalculatedIncludedTax();
                }
            }
        }

        return $this->formatMoney($total, null, null, $format);
    }


    /**
     * Gets the subtotal of the cart with or without tax.
     *
     * @param bool $format
     * @param bool $withDiscount
     * @param bool $withoutTaxIncluded
     *
     * @return string
     */
    public function subTotalAllInstances($format = true, $withDiscount = true, $withoutTaxIncluded = false)
    {
        $total = 0;

        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            $this->setInstance($instance);

            $total += $this->subTotal(false, $withDiscount, $withoutTaxIncluded);
        }


        return $this->formatMoney($total, null, null, $format);
    }

    /**
     * Get the count based on qty, or number of unique items.
     *
     * @param bool $withItemQty
     *
     * @return int
     */
    public function count($withItemQty = true)
    {
        $count = 0;

        foreach ($this->getItems() as $item) {
            if ($withItemQty) {
                $count += $item->qty;
            } else {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get the count based on qty, or number of unique items.
     *
     * @param bool $withItemQty
     *
     * @return int
     */
    public function countAllInstances($withItemQty = true)
    {
        $count = 0;
        $instances = $this->getInstances();

        foreach ($instances as $instance) {
            $this->setInstance($instance);
            $count += $this->count($withItemQty);
        }

        return $count;
    }

    /**
     * Formats the number into a money format based on the locale and international formats.
     *
     * @param $number
     * @param $locale
     * @param $internationalFormat
     * @param $format
     *
     * @return string
     */
    public
    static function formatMoney(
        $number,
        $locale = null,
        $internationalFormat = false,
        $format = true
    ) {
        /*
        $number = number_format($number, 2, '.', '');

        if ($format) {
            setlocale(LC_MONETARY, null);
            setlocale(LC_MONETARY, empty($locale) ? config('shoppingcart.locale', 'en_US.UTF-8') : $locale);

            if (empty($internationalFormat) === true) {
                $internationalFormat = config('shoppingcart.international_format', false);
            }
            $number = currency_format($number);

        }
*/
        if ($format) {
            $number = \Payments::currency($number);
        }
        return $number;
    }

    /**
     * Gets all the fee totals.
     *
     * @param bool $format
     * @param bool $withTax
     *
     * @return string
     */
    public function feeTotals($format = true, $withTax = false)
    {
        $feeTotal = 0;

        foreach ($this->getFees() as $fee) {
            $feeTotal += $fee->amount;

            if ($withTax && $fee->taxable && $fee->tax > 0) {
                $feeTotal += $fee->amount * $fee->tax;
            }
        }

        return $this->formatMoney($feeTotal, null, null, $format);
    }


    /**
     * Gets all the fee totals.
     *
     * @param bool $format
     * @param bool $withTax
     *
     * @return string
     */
    public function feeTotalsAllInstances($format = true, $withTax = false)
    {
        $feeTotal = 0;

        $instances = $this->getInstances();

        foreach ($instances as $instance) {
            $this->setInstance($instance);
            $feeTotal += $this->feeTotals(false, $withTax);
        }

        return $this->formatMoney($feeTotal, null, null, $format);
    }

    /**
     * Gets all the fees on the cart object.
     *
     * @return mixed
     */
    public function getFees()
    {
        return $this->cart->fees;
    }

    /**
     * Gets the total amount discounted.
     *
     * @param bool $format
     * @param bool $withItemDiscounts
     *
     * @return string
     */
    public function totalDiscount($format = true, $withItemDiscounts = true)
    {
        $total = 0;

        if ($withItemDiscounts) {
            /** @var CartItem $item */
            foreach ((array)$this->cart->items as $item) {
                $total += floatval($item->getDiscount(false));
            }
        }
        foreach ($this->cart->coupons as $coupon) {
            if ($coupon->appliedToCart) {
                $total += $coupon->discount();
            }
        }

        return $this->formatMoney($total, null, null, $format);
    }

    /**
     * Gets the total amount discounted.
     *
     * @param bool $format
     * @param bool $withItemDiscounts
     *
     * @return string
     */
    public function totalDiscountAllInstances($format = true, $withItemDiscounts = true)
    {
        $total = 0;

        $instances = $this->getInstances();

        foreach ($instances as $instance) {
            $this->setInstance($instance);

            $total += $this->totalDiscount(false, $withItemDiscounts);
        }


        return $this->formatMoney($total, null, null, $format);
    }

    /**
     * Checks to see if its an item model.
     *
     * @param $itemModel
     *
     * @return bool
     */
    private function isItemModel(
        $itemModel
    ) {
        if (is_object($itemModel) && get_class($itemModel) == config('shoppingcart.item_model')) {
            return true;
        }

        return false;
    }

    /**
     * Gets the item models options based the config.
     *
     * @param Model $itemModel
     * @param array $options
     *
     * @return array
     */
    private function getItemModelOptions(
        Model $itemModel,
        $options = []
    ) {
        $itemOptions = [];
        foreach ($options as $option) {
            $itemOptions[$option] = $this->getFromModel($itemModel, $option);
        }

        return array_filter($itemOptions, function ($value) {
            if ($value !== false && empty($value)) {
                return false;
            }

            return true;
        });
    }

    /**
     * Gets a option from the model.
     *
     * @param Model $itemModel
     * @param $attr
     * @param null $defaultValue
     *
     * @return Model|null
     */
    private function getFromModel(
        Model $itemModel,
        $attr,
        $defaultValue = null
    ) {
        $variable = $itemModel;

        if (!empty($attr)) {
            foreach (explode('.', $attr) as $attr) {
                $variable = \Arr::get($variable, $attr, $defaultValue);
            }
        }

        return $variable;
    }

    /**
     * Removes a coupon from the item.
     *
     * @param null $code
     */
    private function removeCouponFromItems(
        $code = null
    ) {
        foreach ($this->getItems() as $item) {
            if (isset($item->code) && (empty($code) || $item->code == $code)) {
                $item->code = null;
                $item->discount = null;
                $item->couponInfo = [];
            }
        }
    }
}
