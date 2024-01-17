<?php

namespace Corals\Modules\Marketplace\Providers;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Observers\ProductObserver;
use Corals\Modules\Marketplace\Observers\SKUObserver;
use Illuminate\Support\ServiceProvider;

class MarketplaceObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
        SKU::observe(SKUObserver::class);
    }
}
