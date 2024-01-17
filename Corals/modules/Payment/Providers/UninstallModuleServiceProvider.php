<?php

namespace Corals\Modules\Payment\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\Payment\Common\database\migrations\CreateCurrencyTable;
use Corals\Modules\Payment\Common\database\migrations\CreateInvoicesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTaxablesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTaxClassesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTaxesTable;
use Corals\Modules\Payment\Common\database\migrations\CreateTransactionsTable;
use Corals\Modules\Payment\Common\database\migrations\CreateWebhookCallsTable;
use Corals\Modules\Payment\Common\database\seeds\PaymentDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{

    protected $migrations = [
        CreateInvoicesTable::class,
        CreateTransactionsTable::class,
        CreateWebhookCallsTable::class,
        CreateTaxClassesTable::class,
        CreateTaxesTable::class,
        CreateTaxablesTable::class,
        CreateCurrencyTable::class
    ];

    protected function providerBooted()
    {
        $this->dropSchema();
        $paymentDatabaseSeeder = new PaymentDatabaseSeeder();
        $paymentDatabaseSeeder->rollback();
    }
}
