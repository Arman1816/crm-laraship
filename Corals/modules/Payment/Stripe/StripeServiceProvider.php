<?php

namespace Corals\Modules\Payment\Stripe;

use Corals\Modules\Payment\Stripe\Providers\StripeRouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(StripeRouteServiceProvider::class);
    }
}
