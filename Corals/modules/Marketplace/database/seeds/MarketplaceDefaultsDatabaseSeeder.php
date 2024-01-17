<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Illuminate\Database\Seeder;

class MarketplaceDefaultsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // seed marketplace default uncategorized category
        \DB::table('marketplace_categories')->insert([
                ['parent_id' => null,
                    'slug' => 'uncategorized',
                    'description' => 'Default product category',
                    'status' => 'active',
                    'name' => 'Uncategorized',
                ]
            ]
        );
    }
}
