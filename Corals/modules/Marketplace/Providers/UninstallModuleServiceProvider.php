<?php

namespace Corals\Modules\Marketplace\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\Marketplace\database\migrations\CreateMarketplaceTable;
use Corals\Modules\Marketplace\database\migrations\CreateOrdersTable;
use Corals\Modules\Marketplace\database\seeds\MarketplaceDatabaseSeeder;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        CreateMarketplaceTable::class,
        CreateOrdersTable::class,
    ];

    protected function providerBooted()
    {
        $this->dropSchema();
        $marketplaceDatabaseSeeder = new MarketplaceDatabaseSeeder();
        $marketplaceDatabaseSeeder->rollback();

        Media::whereIn('collection_name',
            ['marketplace-category-thumbnail', 'marketplace-brand-thumbnail', 'marketplace-product-gallery', 'marketplace-sku-image'])->delete();
    }
}
