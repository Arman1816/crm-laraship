<?php

namespace Corals\Modules\Marketplace\Hooks;


use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\Shipping;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Modules\Marketplace\Models\UserCart;
use Corals\Modules\Payment\Common\Models\Transaction;

Class HasStore
{


    /**
     * @return mixed
     */
    public function stores()
    {
        return function ($params = []) {

            $relation = $this->hasMany(Store::class, 'user_id');
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;

            }
        };
    }


    /**
     * @return mixed
     */
    public function carts()
    {
        return function ($params = []) {

            $relation = $this->hasMany(UserCart::class, 'user_id');
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;

            }
        };
    }



    /**
     * @return mixed
     */
    public function transactions()
    {
        return function ($params = []) {
            $relation = $this->morphMany(Transaction::class, 'owner');
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;

            }
        };
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return function ($params = []) {

            $relation = $this->hasManyThrough(Product::class, Store::class);
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;

            }
        };
    }

    /**
     * @return mixed
     */
    public function shipping_rules()
    {
        return function ($params = []) {
            $relation = $this->hasManyThrough(Shipping::class, Store::class);
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;
            }
        };
    }

    /**
     * @return mixed
     */
    public function shipping_packages()
    {
        return function ($params = []) {
            $relation = $this->hasManyThrough(Package::class, Store::class);
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;
            }
        };
    }

    public function amazon_imports()
    {
        return function ($params = []) {

            $relation = $this->hasManyThrough( \Corals\Modules\Amazon\Models\Import::class, Store::class);
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;

            }
        };
    }


    /**
     * @return mixed
     */
    public function storeOrders()
    {
        return function ($params = []) {

            $relation = $this->hasManyThrough(Order::class, Store::class);
            if (isset($params['getData']) && $params['getData']) {
                return $relation->getResults();
            } else {
                return $relation;

            }
        };
    }


}
