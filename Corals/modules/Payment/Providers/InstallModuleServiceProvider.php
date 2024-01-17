<?php

namespace Corals\Modules\Payment\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\Payment\Common\database\migrations\CreateCurrencyTable;
use Corals\Modules\Payment\Common\database\migrations\CreateInvoicesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTaxablesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTaxClassesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTaxesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTransactionsTable;
use Corals\Modules\Payment\Common\database\migrations\CreateWebhookCallsTable;
use Corals\Modules\Payment\Common\database\seeds\PaymentDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{

    protected $migrations = [
        CreateInvoicesTable::class,
        CreateWebhookCallsTable::class,
        CreateTaxClassesTable::class,
        CreateTaxesTable::class,
        CreateTaxablesTable::class,
        CreateCurrencyTable::class,
        CreateTransactionsTable::class,
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $paymentDatabaseSeeder = new PaymentDatabaseSeeder();
        $paymentDatabaseSeeder->run();
    }
}
