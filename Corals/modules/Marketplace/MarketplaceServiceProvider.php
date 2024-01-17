<?php

namespace Corals\Modules\Marketplace;

use Corals\Modules\Marketplace\Classes\ShoppingCart as ShoppingCartClass;
use Corals\Modules\Marketplace\Classes\Store;
use Corals\Modules\Marketplace\Console\Commands\CalculateTopSellers;
use Corals\Modules\Marketplace\Console\Commands\SendAbandonedCartsNotification;
use Corals\Modules\Marketplace\Console\Commands\TransferScheduledPayouts;
use Corals\Modules\Marketplace\Facades\Marketplace as MarketplaceFacade;
use Corals\Modules\Marketplace\Facades\OrderManager;
use Corals\Modules\Marketplace\Facades\Shipping;
use Corals\Modules\Marketplace\Facades\ShippingPackages;
use Corals\Modules\Marketplace\Facades\Shop;
use Corals\Modules\Marketplace\Facades\ShoppingCart;
use Corals\Modules\Marketplace\Hooks\HasStore;
use Corals\Modules\Marketplace\Hooks\Marketplace as Marketplace;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Models\Category;
use Corals\Modules\Marketplace\Models\Coupon;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Notifications\AbandonedCartNotification;
use Corals\Modules\Marketplace\Notifications\ImportStatusNotification;
use Corals\Modules\Marketplace\Notifications\OrderReceivedNotification;
use Corals\Modules\Marketplace\Notifications\OrderUpdatedNotification;
use Corals\Modules\Marketplace\Notifications\StoreOrderReceivedNotification;
use Corals\Modules\Marketplace\Notifications\WithdrawalRequestNotification;
use Corals\Modules\Marketplace\Notifications\WithdrawalRequestUpdateNotification;
use Corals\Modules\Marketplace\Providers\MarketplaceAuthServiceProvider;
use Corals\Modules\Marketplace\Providers\MarketplaceObserverServiceProvider;
use Corals\Modules\Marketplace\Providers\MarketplaceRouteServiceProvider;
use Corals\Modules\Utility\Facades\Utility;
use Corals\Settings\Facades\Settings;
use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Models\User;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MarketplaceServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Marketplace');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Marketplace');

        // Load migrations
        //$this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerHooks();
        $this->registerWidgets();
        $this->addEvents();
        $this->registerCustomFieldsModels();
        $this->registerCommand();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/marketplace.php', 'marketplace');
        $this->mergeConfigFrom(__DIR__ . '/config/shoppingcart.php', 'shoppingcart');

        $this->app->register(MarketplaceRouteServiceProvider::class);
        $this->app->register(MarketplaceAuthServiceProvider::class);
        $this->app->register(MarketplaceObserverServiceProvider::class);


        $this->app->singleton(ShoppingCartClass::SERVICE, function ($app) {
            return new ShoppingCartClass($app['session'], $app['events'], $app['auth']);
        });

        //register alias instead of adding it to config/app.php
        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('ShoppingCart', ShoppingCart::class);
            $loader->alias('Marketplace', MarketplaceFacade::class);
            $loader->alias('OrderManager', OrderManager::class);
            $loader->alias('Shop', Shop::class);
            $loader->alias('Store', \Corals\Modules\Marketplace\Facades\Store::class);
            $loader->alias('Shipping', Shipping::class);
            $loader->alias('ShippingPackages', ShippingPackages::class);
        });
        $this->app['router']->pushMiddlewareToGroup('web',
            \Corals\Modules\Marketplace\Middleware\MarketplacetMiddleware::class);

        $this->app->singleton('store', function () {
            return new Store();
        });

        Utility::addToUtilityModules('Marketplace');
    }

    public function registerHooks()
    {
        \Actions::add_action('show_navbar', [Marketplace::class, 'show_store_selector'], 9);
        \Actions::add_action('show_navbar', [Marketplace::class, 'show_cart_icon'], 11);
        \Actions::add_action('user_profile_tabs', [Marketplace::class, 'show_profile_tabs_items'], 11);
        //\Actions::add_action('user_profile_tabs_content', [Marketplace::class, 'show_profile_tabs_content'], 11);
        \Filters::add_filter('dashboard_content', [Marketplace::class, 'dashboard_content'], 8);

        \Actions::add_action('post_create_subscription', [Marketplace::class, 'create_store'], 11);
        \Actions::add_action('post_cancel_subscription', [Marketplace::class, 'set_fallback_plan'], 11);


        \Actions::add_action('show_navbar', [Marketplace::class, 'show_store_selector'], 9);

        \Actions::add_action('post_update_transaction', [Marketplace::class, 'notify_witdrawal_request_transaction_update'], 10);

        \Filters::add_filter('datatable_scopes_campaign', [Marketplace::class, 'add_store_campaigns_query'], 10);
        \Filters::add_filter('select_scopes_campaign', [Marketplace::class, 'add_store_campaigns_query'], 10);
        \Filters::add_filter('select_scopes_zone', [Marketplace::class, 'add_store_zones_query'], 10);


        \Filters::add_filter('datatable_scopes_banner', [Marketplace::class, 'add_store_banners_query'], 10);
        \Filters::add_filter('advert_banner_scopes', [Marketplace::class, 'add_current_store_banners_query'], 10);


        \Filters::add_filter('datatable_scopes_impression', [Marketplace::class, 'add_store_impressions_query'], 10);

        \Filters::add_filter('campaign_request_data', [Marketplace::class, 'set_advertiser_id'], 10);


        $hasStore = new HasStore();
        User::mixin($hasStore);
    }

    public function registerWidgets()
    {
        \Shortcode::addWidget('orders', \Corals\Modules\Marketplace\Widgets\OrdersWidget::class);
        \Shortcode::addWidget('products', \Corals\Modules\Marketplace\Widgets\ProductsWidget::class);
        \Shortcode::addWidget('coupons', \Corals\Modules\Marketplace\Widgets\CouponsWidget::class);
        \Shortcode::addWidget('product_categories', \Corals\Modules\Marketplace\Widgets\ProductCategoriesWidget::class);
        \Shortcode::addWidget('brand_ratio', \Corals\Modules\Marketplace\Widgets\BrandRatioWidget::class);

        \Shortcode::addWidget('my_orders', \Corals\Modules\Marketplace\Widgets\MyOrdersWidget::class);
        \Shortcode::addWidget('my_wishlist', \Corals\Modules\Marketplace\Widgets\MyWishlistWidget::class);
        \Shortcode::addWidget('my_downloads', \Corals\Modules\Marketplace\Widgets\MyDownloadsWidget::class);
        \Shortcode::addWidget('my_private_pages', \Corals\Modules\Marketplace\Widgets\MyPrivatePagesWidget::class);

        \Shortcode::addWidget('my_store_orders', \Corals\Modules\Marketplace\Widgets\MyStoreOrdersWidget::class);
        \Shortcode::addWidget('my_store_products', \Corals\Modules\Marketplace\Widgets\MyStoreProductsWidget::class);
        \Shortcode::addWidget('my_store_sales', \Corals\Modules\Marketplace\Widgets\MyStoreRevenueWidget::class);
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Product::class, 'Product (Marketplace)');
        Settings::addCustomFieldModel(SKU::class);
        Settings::addCustomFieldModel(Brand::class);
        Settings::addCustomFieldModel(\Corals\Modules\Marketplace\Models\Store::class);
        Settings::addCustomFieldModel(Category::class);
        Settings::addCustomFieldModel(Attribute::class);
        Settings::addCustomFieldModel(Tag::class);
        Settings::addCustomFieldModel(Coupon::class);
        Settings::addCustomFieldModel(\Corals\Modules\Marketplace\Models\Shipping::class);
    }

    protected function addEvents()
    {
        CoralsNotification::addEvent(
            'notifications.marketplace.order.received',
            'Marketplace Order Received',
            OrderReceivedNotification::class);

        CoralsNotification::addEvent(
            'notifications.marketplace.store_order.received',
            'Store Order Received',
            StoreOrderReceivedNotification::class);

        CoralsNotification::addEvent(
            'notifications.marketplace.order.updated',
            'Order Updated',
            OrderUpdatedNotification::class);

        CoralsNotification::addEvent(
            'notifications.marketplace.withdrawal.requested',
            'Marketplace Withdrawal Requested',
            WithdrawalRequestNotification::class);

        CoralsNotification::addEvent(
            'notifications.marketplace.withdrawal_request.updated',
            'Marketplace Withdrawal Request Updated',
            WithdrawalRequestUpdateNotification::class);

        CoralsNotification::addEvent(
            'notifications.marketplace.abandoned_cart',
            'Marketplace Abandoned Cart',
            AbandonedCartNotification::class);

        CoralsNotification::addEvent(
            'notifications.marketplace.import_status',
            'Marketplace Products Import Status',
            ImportStatusNotification::class);
    }

    public function provides()
    {
        return [
            'store'
        ];
    }

    protected function registerCommand()
    {
        $this->commands(CalculateTopSellers::class);
        $this->commands(SendAbandonedCartsNotification::class);
        $this->commands(TransferScheduledPayouts::class);
    }
}
