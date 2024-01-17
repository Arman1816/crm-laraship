<?php

namespace Corals\Modules\Subscriptions;

use Corals\Modules\CMS\Models\Category;
use Corals\Modules\Payment\Common\Events\InvoicePaidEvent;
use Corals\Modules\Subscriptions\Console\Commands\SubscriptionCheckup;
use Corals\Modules\Subscriptions\Events\SubscriptionCreated;
use Corals\Modules\Subscriptions\Facades\SubscriptionProducts;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager;
use Corals\Modules\Subscriptions\Facades\UsageManager;
use Corals\Modules\Subscriptions\Hooks\Billing;
use Corals\Modules\Subscriptions\Hooks\Subscribable;
use Corals\Modules\Subscriptions\Hooks\Subscription;
use Corals\Modules\Subscriptions\Listeners\HandleSubscriptionCreated;
use Corals\Modules\Subscriptions\Listeners\InvoicePaidListener;
use Corals\Modules\Subscriptions\Models\Feature;
use Corals\Modules\Subscriptions\Models\Plan;
use Corals\Modules\Subscriptions\Models\Product;
use Corals\Modules\Subscriptions\Notifications\SubscriptionCancelledNotification;
use Corals\Modules\Subscriptions\Notifications\SubscriptionCreatedNotification;
use Corals\Modules\Subscriptions\Notifications\SubscriptionRenewalInvoiceNotification;
use Corals\Modules\Subscriptions\Notifications\SubscriptionRenewalNotification;
use Corals\Modules\Subscriptions\Notifications\SubscriptionSwappedNotification;
use Corals\Modules\Subscriptions\Providers\SubscriptionsAuthServiceProvider;
use Corals\Modules\Subscriptions\Providers\SubscriptionsObserverServiceProvider;
use Corals\Modules\Subscriptions\Providers\SubscriptionsRouteServiceProvider;
use Corals\Modules\Subscriptions\Widgets\FeaturesUsageStatistics;
use Corals\Settings\Facades\Settings;
use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Models\User;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SubscriptionsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Subscriptions');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Subscriptions');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerShortcode();
        $this->registerWidgets();
        $this->registerHooks();
        $this->registerCustomFieldsModels();
        $this->addEvents();
        $this->registerEventsListeners();

        $this->commands(SubscriptionCheckup::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/subscriptions.php', 'subscriptions');

        $this->app->register(SubscriptionsRouteServiceProvider::class);
        $this->app->register(SubscriptionsAuthServiceProvider::class);
        $this->app->register(SubscriptionsObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('SubscriptionProducts', SubscriptionProducts::class);
            $loader->alias('UsageManager', UsageManager::class);
            $loader->alias('SubscriptionsManager', SubscriptionsManager::class);
        });
    }

    public function registerShortcode()
    {
        \Shortcode::add('pricing', function ($key) {
            $view = 'pricing';
            if (is_numeric($key)) {
                $product = Product::where('id', $key)->active()->first();
                view()->share(['product' => $product, 'product_id' => $key]);
                if (view()->exists($view)) {
                    return "<?php  echo \$__env->make('$view')->render(); ?>";
                }
            } else {
                //Assume Product Object passed
                return "<?php  echo \$__env->make('$view',['product'=>{$key}])->render(); ?>";
            }
        });
    }

    public function registerWidgets()
    {
        \Shortcode::addWidget('subscriptions', \Corals\Modules\Subscriptions\Widgets\SubscriptionsWidget::class);
        \Shortcode::addWidget('plans', \Corals\Modules\Subscriptions\Widgets\PlansWidget::class);
        \Shortcode::addWidget('subscription_ratio',
            \Corals\Modules\Subscriptions\Widgets\SubscriptionRatioWidget::class);
        \Shortcode::addWidget('features_usage_statistics', FeaturesUsageStatistics::class);
    }

    public function registerHooks()
    {
        \Actions::add_action('post_delete_user', [Subscription::class, 'delete_customer_subscription'], 10);

        \Filters::add_filter('corals_middleware', [Subscription::class, 'subscriptions_middleware'], 10);
        \Filters::add_filter('dashboard_content', [Subscription::class, 'dashboard_content1'], 10);
        \Filters::add_filter('dashboard_content', [Subscription::class, 'dashboard_content2'], 20);
        \Actions::add_action('display_profile', [Subscription::class, 'profile_subscriptions'], 10);


        \Filters::add_filter('active_profile_tab', [Subscription::class, 'set_subscription_active_tab'], 10);
        \Filters::add_filter('active_dashboard_tab', [Subscription::class, 'set_subscription_active_tab'], 10);

        \Actions::add_action('user_profile_tabs', [Subscription::class, 'show_profile_subscription_tabs'], 10);
        \Actions::add_action('user_profile_tabs_content',
            [Subscription::class, 'show_profile_subscription_tabs_content'], 10);


        $billable = new Billing();
        User::mixin($billable);

        $subscribable = new Subscribable();
        Category::mixin($subscribable);
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Feature::class);
        Settings::addCustomFieldModel(Plan::class);
        Settings::addCustomFieldModel(Product::class);
        Settings::addCustomFieldModel(Models\Subscription::class);
    }

    protected function addEvents()
    {
        CoralsNotification::addEvent(
            'notifications.subscription.created',
            'Subscription Created',
            SubscriptionCreatedNotification::class);

        CoralsNotification::addEvent(
            'notifications.subscription.approved',
            'Subscription Approved',
            SubscriptionCreatedNotification::class);

        CoralsNotification::addEvent(
            'notifications.subscription.swapped',
            'Subscription Swapped',
            SubscriptionSwappedNotification::class);

        CoralsNotification::addEvent(
            'notifications.subscription.cancelled',
            'Subscription Cancelled',
            SubscriptionCancelledNotification::class);

        CoralsNotification::addEvent(
            'notifications.subscription.subscription_renewal_notification',
            'Subscription Renewal',
            SubscriptionRenewalNotification::class);

        CoralsNotification::addEvent(
            'notifications.subscription.subscription_renewal_invoice',
            'Subscription Renewal Invoice',
            SubscriptionRenewalInvoiceNotification::class);
    }

    /**
     *
     */
    protected function registerEventsListeners(): void
    {
        Event::listen(
            SubscriptionCreated::class,
            [HandleSubscriptionCreated::class, 'handle']
        );

        Event::listen(InvoicePaidEvent::class, InvoicePaidListener::class);
    }
}
