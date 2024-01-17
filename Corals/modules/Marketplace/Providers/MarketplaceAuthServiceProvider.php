<?php

namespace Corals\Modules\Marketplace\Providers;

use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\AttributeSet;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Models\Category;
use Corals\Modules\Marketplace\Models\Coupon;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\Shipping;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Policies\AttributePolicy;
use Corals\Modules\Marketplace\Policies\AttributeSetPolicy;
use Corals\Modules\Marketplace\Policies\BrandPolicy;
use Corals\Modules\Marketplace\Policies\CategoryPolicy;
use Corals\Modules\Marketplace\Policies\CouponPolicy;
use Corals\Modules\Marketplace\Policies\OrderPolicy;
use Corals\Modules\Marketplace\Policies\PackagePolicy;
use Corals\Modules\Marketplace\Policies\ProductPolicy;
use Corals\Modules\Marketplace\Policies\ShippingPolicy;
use Corals\Modules\Marketplace\Policies\SKUPolicy;
use Corals\Modules\Marketplace\Policies\StorePolicy;
use Corals\Modules\Marketplace\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class MarketplaceAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Coupon::class => CouponPolicy::class,
        Shipping::class => ShippingPolicy::class,
        Product::class => ProductPolicy::class,
        SKU::class => SKUPolicy::class,
        Store::class => StorePolicy::class,
        Category::class => CategoryPolicy::class,
        Brand::class => BrandPolicy::class,
        Order::class => OrderPolicy::class,
        Attribute::class => AttributePolicy::class,
        Tag::class => TagPolicy::class,
        Package::class => PackagePolicy::class,
        AttributeSet::class => AttributeSetPolicy::class,
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
