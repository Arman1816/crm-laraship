<?php

namespace Corals\Modules\Payment\Providers;

use Corals\Modules\Payment\Common\Models\Currency;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Payment\Common\Models\Tax;
use Corals\Modules\Payment\Common\Models\TaxClass;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\Modules\Payment\Common\Policies\CurrencyPolicy;
use Corals\Modules\Payment\Common\Policies\InvoicePolicy;
use Corals\Modules\Payment\Common\Policies\TaxClassPolicy;
use Corals\Modules\Payment\Common\Policies\TaxPolicy;
use Corals\Modules\Payment\Common\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class PaymentAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        Invoice::class => InvoicePolicy::class,
        Transaction::class => TransactionPolicy::class,
        Currency::class => CurrencyPolicy::class,
        TaxClass::class => TaxClassPolicy::class,
        Tax::class => TaxPolicy::class,
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
