<?php

namespace Corals\Modules\Marketplace\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\Marketplace\database\migrations\CreateMarketplaceTable;
use Corals\Modules\Marketplace\database\migrations\CreateOrdersTable;
use Corals\Modules\Marketplace\database\seeds\MarketplaceDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $migrations = [
        CreateMarketplaceTable::class,
        CreateOrdersTable::class,
    ];

    protected $module_public_path = __DIR__ . '/../public';

    protected function providerBooted()
    {
        $this->createSchema();

        $marketplaceSeeder = new MarketplaceDatabaseSeeder();
        $marketplaceSeeder->run();
    }
}
