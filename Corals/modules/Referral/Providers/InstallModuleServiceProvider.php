<?php

namespace Corals\Modules\Referral\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\Referral\database\migrations\ReferralTables;
use Corals\Modules\Referral\database\seeds\ReferralDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $migrations = [
        ReferralTables::class
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $referralProgramDatabaseSeeder = new ReferralDatabaseSeeder();

        $referralProgramDatabaseSeeder->run();
    }
}
