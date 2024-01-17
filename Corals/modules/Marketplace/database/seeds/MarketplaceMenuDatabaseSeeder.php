<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Corals\User\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketplaceMenuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendor_role = Role::where('name', 'vendor')->first();

        $vendor_role_id = $vendor_role->id;

        $marketplace_menu_id = DB::table('menus')->insertGetId([
            'parent_id' => 1,// admin
            'key' => 'marketplace',
            'url' => null,
            'active_menu_url' => 'marketplace*',
            'name' => 'Marketplace',
            'description' => 'Marketplace Menu Item',
            'icon' => 'fa fa-sitemap',
            'target' => null,
            'roles' => '["1","2","' . $vendor_role_id . '"]',
            'order' => 0
        ]);

        // seed subscriptions children menu
        DB::table('menus')->insert([
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.store.resource_url'),
                    'active_menu_url' => config('marketplace.models.store.resource_url') . '*',
                    'name' => 'Stores',
                    'description' => 'Stores List Menu Item',
                    'icon' => 'fa fa-shopping-cart',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.product.resource_url'),
                    'active_menu_url' => config('marketplace.models.product.resource_url') . '*',
                    'name' => 'Products',
                    'description' => 'Products List Menu Item',
                    'icon' => 'fa fa-cube',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.order.resource_url') . '/store',
                    'active_menu_url' => config('marketplace.models.order.resource_url') . '/store',
                    'name' => 'Store Orders',
                    'description' => 'My Store Orders Menu Item',
                    'icon' => 'fa fa-send-o',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.transaction.resource_url'),
                    'active_menu_url' => config('marketplace.models.transaction.resource_url') . '*',
                    'name' => 'Store Transactions',
                    'description' => 'Store Transactions Menu Item',
                    'icon' => 'fa fa-exchange',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => 'marketplace/shop',
                    'active_menu_url' => 'marketplace/shop*',
                    'name' => 'Shop',
                    'description' => 'Shop Menu Item',
                    'icon' => 'fa fa-building',
                    'target' => null,
                    'roles' => '["2"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => 'marketplace/downloads/my',
                    'active_menu_url' => 'marketplace/downloads/my',
                    'name' => 'My Downloads',
                    'description' => 'My Downloads Menu Item',
                    'icon' => 'fa fa-download',
                    'target' => null,
                    'roles' => '["2"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.order.resource_url') . '/my',
                    'active_menu_url' => config('marketplace.models.order.resource_url') . '/my',
                    'name' => 'My Orders',
                    'description' => 'My Orders Menu Item',
                    'icon' => 'fa fa-send-o',
                    'target' => null,
                    'roles' => '["2"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.wishlist.resource_url') . '/my',
                    'active_menu_url' => config('marketplace.models.wishlist.resource_url') . '/my',
                    'name' => 'My Wishlist',
                    'description' => 'My Wishlist Menu Item',
                    'icon' => 'fa fa-heart',
                    'target' => null,
                    'roles' => '["2"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.follow.resource_url') . '/my',
                    'active_menu_url' => config('marketplace.models.follow.resource_url') . '/my',
                    'name' => 'My Following Stores',
                    'description' => 'Store Follow',
                    'icon' => 'fa fa-star-o',
                    'target' => null,
                    'roles' => '["2"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.order.resource_url'),
                    'active_menu_url' => config('marketplace.models.order.resource_url'),
                    'name' => 'Orders',
                    'description' => 'Orders Menu Item',
                    'icon' => 'fa fa-send-o',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],

                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.category.resource_url'),
                    'active_menu_url' => config('marketplace.models.category.resource_url') . '*',
                    'name' => 'Categories',
                    'description' => 'Categories List Menu Item',
                    'icon' => 'fa fa-folder-open',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.attribute_set.resource_url'),
                    'active_menu_url' => config('marketplace.models.attribute_set.resource_url') . '*',
                    'name' => 'Attribute Sets',
                    'description' => 'Attribute Sets List Menu Item',
                    'icon' => 'fa fa-sliders',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.attribute.resource_url'),
                    'active_menu_url' => config('marketplace.models.attribute.resource_url') . '*',
                    'name' => 'Attributes',
                    'description' => 'Attributes List Menu Item',
                    'icon' => 'fa fa-sliders',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.tag.resource_url'),
                    'active_menu_url' => config('marketplace.models.tag.resource_url') . '*',
                    'name' => 'Tags',
                    'description' => 'Tags List Menu Item',
                    'icon' => 'fa fa-tags',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.brand.resource_url'),
                    'active_menu_url' => config('marketplace.models.brand.resource_url') . '*',
                    'name' => 'Brands',
                    'description' => 'Brands List Menu Item',
                    'icon' => 'fa fa-cubes',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => config('marketplace.models.coupon.resource_url'),
                    'active_menu_url' => config('marketplace.models.coupon.resource_url') . '*',
                    'name' => 'Coupons',
                    'description' => 'Coupons List Menu Item',
                    'icon' => 'fa fa-gift',
                    'target' => null,
                    'roles' => '["1","' . $vendor_role_id . '"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => 'marketplace/settings',
                    'active_menu_url' => 'marketplace/settings',
                    'name' => 'Settings',
                    'description' => 'Settings Menu Item',
                    'icon' => 'fa fa-cog',
                    'target' => null,
                    'roles' => '["1"]',
                    'order' => 0
                ],
                [
                    'parent_id' => $marketplace_menu_id,
                    'key' => null,
                    'url' => 'marketplace/store/settings',
                    'active_menu_url' => 'marketplace/store/settings',
                    'name' => 'Store Settings',
                    'description' => 'Store Settings Menu Item',
                    'icon' => 'fa fa-cog',
                    'target' => null,
                    'roles' => '["' . $vendor_role_id . '"]',
                    'order' => 0
                ],
            ]
        );

        $shipping_menu_id = DB::table('menus')->insertGetId([
            'parent_id' => $marketplace_menu_id,
            'key' => 'shipping',
            'url' => null,
            'active_menu_url' => config('marketplace.models.shipping.resource_url') . '*',
            'name' => 'Shipping',
            'description' => 'Shippings Management Menu Item',
            'icon' => 'fa fa-truck',
            'target' => null,
            'roles' => '["1","' . $vendor_role_id . '"]',
            'order' => 0
        ]);

        // seed subscriptions children menu
        DB::table('menus')->insert([
            [
                'parent_id' => $shipping_menu_id,
                'key' => null,
                'url' => config('marketplace.models.shipping.resource_url'),
                'active_menu_url' => config('marketplace.models.shipping.resource_url'),
                'name' => 'Shipping Rules',
                'description' => 'Shippings Listing Menu Item',
                'icon' => 'fa fa-truck',
                'target' => null,
                'roles' => '["1","' . $vendor_role_id . '"]',
                'order' => 0
            ],
            [
                'parent_id' => $shipping_menu_id,
                'key' => null,
                'url' => config('marketplace.models.package.resource_url'),
                'active_menu_url' => config('marketplace.models.package.resource_url'),
                'name' => 'Package Templates',
                'description' => 'Packages Listing Menu Item',
                'icon' => 'fa fa-cube',
                'target' => null,
                'roles' => '["1","' . $vendor_role_id . '"]',
                'order' => 0
            ],
            [
                'parent_id' => $shipping_menu_id,
                'key' => null,
                'url' => config('marketplace.models.shipping.resource_url') . '/upload',
                'active_menu_url' => config('marketplace.models.shipping.resource_url') . '/upload',
                'name' => 'Import Rules',
                'description' => 'Shipping Rules Upload  Menu Item',
                'icon' => 'fa fa-upload',
                'target' => null,
                'roles' => '["1","' . $vendor_role_id . '"]',
                'order' => 0
            ],
        ]);


        \Menus::attachMenuItems(['advert', 'adverts-campaigns', 'adverts-banners', 'adverts-impressions'],
            $vendor_role);
    }
}
