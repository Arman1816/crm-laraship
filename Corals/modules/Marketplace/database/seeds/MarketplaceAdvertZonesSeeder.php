<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class MarketplaceAdvertZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        \DB::table('advert_zones')->insert(array(
            0 =>
                array(
                    'name' => 'Store Header',
                    'key' => 'store-header',
                    'dimension' => '728x90',
                    'notes' => NULL,
                    'status' => 'active',
                    'website_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_at' => NULL,
                    'created_at' => '2018-03-29 13:17:21',
                    'updated_at' => '2018-03-29 13:17:21',
                ),
            1 =>
                array(
                    'name' => 'Store Sidebar',
                    'key' => 'store-sidebar',
                    'dimension' => '728x90',
                    'notes' => NULL,
                    'status' => 'active',
                    'website_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_at' => NULL,
                    'created_at' => '2018-03-29 13:17:21',
                    'updated_at' => '2018-03-29 13:17:21',
                ),

            2 =>
                array(
                    'name' => 'Store Footer',
                    'key' => 'store-footer',
                    'dimension' => '728x90',
                    'notes' => NULL,
                    'status' => 'active',
                    'website_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_at' => NULL,
                    'created_at' => '2018-03-29 13:17:48',
                    'updated_at' => '2018-03-29 13:17:48',
                ),
            3 =>
                array(
                    'name' => 'Shop Sidebar',
                    'key' => 'shop-sidebar',
                    'dimension' => '250x250',
                    'notes' => NULL,
                    'status' => 'active',
                    'website_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_at' => NULL,
                    'created_at' => '2018-03-29 13:17:48',
                    'updated_at' => '2018-03-29 13:17:48',
                ),
            4 =>
                array(
                    'name' => 'Shop Header',
                    'key' => 'shop-header',
                    'dimension' => '728x90',
                    'notes' => NULL,
                    'status' => 'active',
                    'website_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_at' => NULL,
                    'created_at' => '2018-03-29 13:17:48',
                    'updated_at' => '2018-03-29 13:17:48',
                ),
            5 =>
                array(
                    'name' => 'Shop Footer',
                    'key' => 'shop-footer',
                    'dimension' => '728x90',
                    'notes' => NULL,
                    'status' => 'active',
                    'website_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_at' => NULL,
                    'created_at' => '2018-03-29 13:17:48',
                    'updated_at' => '2018-03-29 13:17:48',
                )


        ));

    }
}
