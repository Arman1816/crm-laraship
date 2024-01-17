<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Carbon\Carbon;
use Corals\User\Models\Role;
use Illuminate\Database\Seeder;

class MarketplacePermissionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('roles')->insert([
            [
                'name' => 'vendor',
                'label' => 'Vendor',
                'guard_name' => config('auth.defaults.guard'),
                'subscription_required' => 0,
                'dashboard_theme' => 'corals-marketplace-master',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        \DB::table('permissions')->insert([
            [
                'name' => 'Administrations::admin.marketplace',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::cart.access',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::shop.access',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::checkout.access',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::my_orders.access',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::store_orders.access',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::order.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::settings.access',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::product.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::product.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::product.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::product.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::order.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::order.view_payment_details',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::order.update_payment_details',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::coupon.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::coupon.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::coupon.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::coupon.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::shipping.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::shipping.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::shipping.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::shipping.upload',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::shipping.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::package.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::package.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::package.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::package.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::category.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::category.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::category.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::category.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::tag.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::tag.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::tag.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::tag.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::brand.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Marketplace::brand.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Marketplace::brand.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Marketplace::brand.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Marketplace::attribute.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::attribute.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::attribute.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::attribute.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::store.view',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::store.create',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::store.update',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::store.delete',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Marketplace::transaction.withdraw',
                'guard_name' => config('auth.defaults.guard'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
        ]);

        $member_role = Role::where('name', 'member')->first();

        if ($member_role) {
            $member_role->update([
                'dashboard_theme' => 'corals-marketplace-master'
            ]);
            $member_role->forgetCachedPermissions();
            $member_role->givePermissionTo('Marketplace::cart.access');
            $member_role->givePermissionTo('Marketplace::shop.access');
            $member_role->givePermissionTo('Marketplace::checkout.access');
            $member_role->givePermissionTo('Marketplace::my_orders.access');
            $member_role->givePermissionTo('Marketplace::order.view_payment_details');


        }

        $vendor_role = Role::where('name', 'vendor')->first();

        if ($vendor_role) {
            $vendor_role->forgetCachedPermissions();

            $vendor_role->givePermissionTo('Marketplace::cart.access');
            $vendor_role->givePermissionTo('Marketplace::shop.access');
            $vendor_role->givePermissionTo('Marketplace::checkout.access');
            $vendor_role->givePermissionTo('Marketplace::my_orders.access');




            $vendor_role->givePermissionTo('Marketplace::product.view');
            $vendor_role->givePermissionTo('Marketplace::product.create');
            $vendor_role->givePermissionTo('Marketplace::product.update');
            $vendor_role->givePermissionTo('Marketplace::product.delete');
            $vendor_role->givePermissionTo('Marketplace::store_orders.access');
            $vendor_role->givePermissionTo('Marketplace::store.update');
            $vendor_role->givePermissionTo('Marketplace::coupon.view');
            $vendor_role->givePermissionTo('Marketplace::coupon.create');
            $vendor_role->givePermissionTo('Marketplace::coupon.update');
            $vendor_role->givePermissionTo('Marketplace::coupon.delete');
            $vendor_role->givePermissionTo('Marketplace::shipping.view');
            $vendor_role->givePermissionTo('Marketplace::shipping.create');
            $vendor_role->givePermissionTo('Marketplace::shipping.update');
            $vendor_role->givePermissionTo('Marketplace::shipping.upload');
            $vendor_role->givePermissionTo('Marketplace::shipping.delete');

            $vendor_role->givePermissionTo('Marketplace::package.view');
            $vendor_role->givePermissionTo('Marketplace::package.create');
            $vendor_role->givePermissionTo('Marketplace::package.update');
            $vendor_role->givePermissionTo('Marketplace::package.delete');

            $vendor_role->givePermissionTo('Subscriptions::product.view');
            $vendor_role->givePermissionTo('Subscriptions::subscriptions.subscribe');

            $vendor_role->givePermissionTo('Marketplace::order.update');
            $vendor_role->givePermissionTo('Payment::invoices.view');
            $vendor_role->givePermissionTo('Payment::transaction.view');

            $vendor_role->givePermissionTo('Marketplace::transaction.withdraw');


            $vendor_role->givePermissionTo('Advert::campaign.create');
            $vendor_role->givePermissionTo('Advert::campaign.view');
            $vendor_role->givePermissionTo('Advert::campaign.delete');
            $vendor_role->givePermissionTo('Advert::campaign.update');

            $vendor_role->givePermissionTo('Advert::banner.create');
            $vendor_role->givePermissionTo('Advert::banner.view');
            $vendor_role->givePermissionTo('Advert::banner.delete');
            $vendor_role->givePermissionTo('Advert::banner.update');
            $vendor_role->givePermissionTo('Advert::impression.view');


            $vendor_role->givePermissionTo('Messaging::discussion.view');
            $vendor_role->givePermissionTo('Messaging::discussion.create');
            $vendor_role->givePermissionTo('Messaging::discussion.update');
            $vendor_role->givePermissionTo('Messaging::discussion.delete');

            $vendor_role->givePermissionTo('Messaging::message.view');
            $vendor_role->givePermissionTo('Messaging::message.create');
            $vendor_role->givePermissionTo('Messaging::message.update');
            $vendor_role->givePermissionTo('Messaging::message.delete');

            $member_role->givePermissionTo('Messaging::participation.set_status');


            \Settings::set('marketplace_general_vendor_role', $vendor_role->id);
        }
    }
}
